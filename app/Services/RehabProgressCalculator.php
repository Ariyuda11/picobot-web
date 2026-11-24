<?php

namespace App\Services;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Menghitung indikator progres rehabilitasi dari data riwayat latihan.
 *
 * Kelas ini sengaja dibuat tanpa ketergantungan pada Laravel (hanya PHP murni)
 * agar logika perhitungannya mudah diuji dan dijelaskan.
 *
 * Setiap sesi berupa array:
 * [
 *     'performed_at'     => DateTimeInterface,
 *     'exercise_name'    => string,
 *     'intensity'        => 'Ringan'|'Sedang'|'Berat',
 *     'duration_minutes' => int,
 *     'repetitions'      => ?int,
 *     'pain_level'       => ?int (0-10),
 * ]
 */
class RehabProgressCalculator
{
    /** Pilihan periode pemantauan (hari). null = seluruh riwayat. */
    public const PERIODS = [7, 30, 90];

    /** Jumlah sesi minimal agar tren dapat dihitung (2 sesi per paruh). */
    public const MIN_SESSIONS_FOR_TREND = 4;

    /**
     * Selisih rata-rata skor nyeri (skala 0-10) yang dianggap bermakna.
     * Mengacu pada perubahan bermakna klinis ±2 poin pada skala NRS 0-10
     * (Farrar et al., 2001). Perlu divalidasi dengan fisioterapis.
     */
    public const PAIN_DELTA_THRESHOLD = 2.0;

    /** Perubahan relatif rata-rata durasi latihan yang dianggap bermakna (10%). */
    public const DURATION_CHANGE_THRESHOLD = 0.10;

    /** Pasien dianggap tidak aktif bila tidak berlatih lebih dari N hari. */
    public const INACTIVE_DAYS_THRESHOLD = 7;

    /** Rata-rata nyeri terbaru pada atau di atas nilai ini perlu diperhatikan. */
    public const HIGH_PAIN_THRESHOLD = 7.0;

    public const STATUS_LABELS = [
        'membaik' => 'Membaik',
        'stabil' => 'Stabil',
        'perlu_perhatian' => 'Perlu Perhatian',
        'belum_cukup_data' => 'Belum Cukup Data',
    ];

    private DateTimeImmutable $now;

    public function __construct(?DateTimeInterface $now = null)
    {
        $this->now = $now
            ? DateTimeImmutable::createFromInterface($now)
            : new DateTimeImmutable();
    }

    /**
     * @param  array<int, array<string, mixed>>  $sessions  seluruh riwayat latihan pasien
     * @param  int|null  $periodDays  7, 30, 90, atau null untuk seluruh riwayat
     */
    public function summarize(array $sessions, ?int $periodDays = 30): array
    {
        // Sesi bertanggal di masa depan diabaikan
        $all = array_values(array_filter(
            $this->sortAscending($sessions),
            fn (array $s) => $s['performed_at'] <= $this->now
        ));
        $lastSession = $all ? end($all)['performed_at'] : null;

        $periodStart = $this->resolvePeriodStart($all, $periodDays);
        $inPeriod = array_values(array_filter(
            $all,
            fn (array $s) => $s['performed_at'] >= $periodStart && $s['performed_at'] <= $this->now
        ));

        $daysSinceLast = $lastSession
            ? (int) $this->startOfDay($lastSession)->diff($this->startOfDay($this->now))->days
            : null;

        $periodLengthDays = max(1, (int) $this->startOfDay($periodStart)->diff($this->startOfDay($this->now))->days + 1);

        $totals = $this->totals($inPeriod, $periodLengthDays);
        $totals['last_session_at'] = $lastSession;
        $totals['days_since_last'] = $daysSinceLast;

        $painTrend = $this->painTrend($inPeriod);
        $durationTrend = $this->durationTrend($inPeriod);

        return [
            'period' => [
                'days' => $periodDays,
                'start' => $periodStart,
                'end' => $this->now,
                'length_days' => $periodLengthDays,
            ],
            'totals' => $totals,
            'trend' => [
                'pain' => $painTrend,
                'duration' => $durationTrend,
            ],
            'status' => $this->status($inPeriod, $painTrend, $durationTrend, $daysSinceLast),
            'weekly' => $this->weekly($inPeriod),
            'by_exercise' => $this->byExercise($inPeriod),
            'intensity' => $this->intensityDistribution($inPeriod),
            'chart' => $this->chartPoints($inPeriod),
        ];
    }

    private function sortAscending(array $sessions): array
    {
        usort($sessions, fn ($a, $b) => $a['performed_at'] <=> $b['performed_at']);

        return array_values($sessions);
    }

    private function resolvePeriodStart(array $all, ?int $periodDays): DateTimeImmutable
    {
        if ($periodDays !== null) {
            return $this->startOfDay($this->now)->modify('-'.($periodDays - 1).' days');
        }

        // Seluruh riwayat: mulai dari sesi pertama (atau hari ini bila belum ada data)
        return $all
            ? $this->startOfDay($all[0]['performed_at'])
            : $this->startOfDay($this->now);
    }

    private function totals(array $sessions, int $periodLengthDays): array
    {
        $count = count($sessions);
        $minutes = array_sum(array_column($sessions, 'duration_minutes'));
        $pains = $this->painValues($sessions);

        $activeDays = count(array_unique(array_map(
            fn (array $s) => $s['performed_at']->format('Y-m-d'),
            $sessions
        )));

        // Frekuensi per minggu dihitung dari panjang periode, minimal 1 minggu
        $weeks = max(1, $periodLengthDays / 7);

        return [
            'sessions' => $count,
            'total_minutes' => $minutes,
            'average_duration' => $count ? round($minutes / $count, 1) : 0,
            'average_pain' => $pains ? round(array_sum($pains) / count($pains), 1) : null,
            'active_days' => $activeDays,
            'sessions_per_week' => round($count / $weeks, 1),
        ];
    }

    /**
     * Membandingkan rata-rata nyeri paruh awal vs paruh akhir sesi pada periode.
     * Penurunan nyeri = arah yang baik.
     */
    private function painTrend(array $sessions): ?array
    {
        $withPain = array_values(array_filter($sessions, fn ($s) => $s['pain_level'] !== null));

        if (count($withPain) < self::MIN_SESSIONS_FOR_TREND) {
            return null;
        }

        [$earlier, $recent] = $this->splitHalves($withPain);
        $earlierAvg = $this->average($this->painValues($earlier));
        $recentAvg = $this->average($this->painValues($recent));
        $delta = round($recentAvg - $earlierAvg, 1);

        $direction = 'stabil';
        if ($delta <= -self::PAIN_DELTA_THRESHOLD) {
            $direction = 'turun';
        } elseif ($delta >= self::PAIN_DELTA_THRESHOLD) {
            $direction = 'naik';
        }

        return [
            'earlier' => round($earlierAvg, 1),
            'recent' => round($recentAvg, 1),
            'delta' => $delta,
            'direction' => $direction,
        ];
    }

    /**
     * Membandingkan rata-rata durasi paruh awal vs paruh akhir sesi pada periode.
     * Kenaikan durasi = arah yang baik (toleransi latihan meningkat).
     */
    private function durationTrend(array $sessions): ?array
    {
        if (count($sessions) < self::MIN_SESSIONS_FOR_TREND) {
            return null;
        }

        [$earlier, $recent] = $this->splitHalves($sessions);
        $earlierAvg = $this->average(array_column($earlier, 'duration_minutes'));
        $recentAvg = $this->average(array_column($recent, 'duration_minutes'));
        $change = $earlierAvg > 0 ? ($recentAvg - $earlierAvg) / $earlierAvg : 0.0;

        $direction = 'stabil';
        if ($change >= self::DURATION_CHANGE_THRESHOLD) {
            $direction = 'naik';
        } elseif ($change <= -self::DURATION_CHANGE_THRESHOLD) {
            $direction = 'turun';
        }

        return [
            'earlier' => round($earlierAvg, 1),
            'recent' => round($recentAvg, 1),
            'change_percent' => round($change * 100, 1),
            'direction' => $direction,
        ];
    }

    /**
     * Status progres berbasis aturan (rule-based). Bukan diagnosis medis.
     */
    private function status(array $sessions, ?array $pain, ?array $duration, ?int $daysSinceLast): array
    {
        $reasons = [];

        if ($daysSinceLast !== null && $daysSinceLast > self::INACTIVE_DAYS_THRESHOLD) {
            $reasons[] = "Tidak ada latihan dalam {$daysSinceLast} hari terakhir.";
        }

        if (count($sessions) < self::MIN_SESSIONS_FOR_TREND) {
            $reasons[] = 'Jumlah sesi pada periode ini kurang dari '
                .self::MIN_SESSIONS_FOR_TREND.' sehingga tren belum dapat dihitung.';

            $key = ($daysSinceLast !== null && $daysSinceLast > self::INACTIVE_DAYS_THRESHOLD)
                ? 'perlu_perhatian'
                : 'belum_cukup_data';

            return $this->statusResult($key, $reasons);
        }

        $attention = ! empty($reasons);

        if ($pain && $pain['direction'] === 'naik') {
            $attention = true;
            $reasons[] = "Rata-rata nyeri naik dari {$pain['earlier']} menjadi {$pain['recent']}.";
        }

        if ($pain && $pain['recent'] >= self::HIGH_PAIN_THRESHOLD) {
            $attention = true;
            $reasons[] = "Rata-rata nyeri terbaru tinggi ({$pain['recent']} / 10).";
        }

        if ($attention) {
            return $this->statusResult('perlu_perhatian', $reasons);
        }

        $improving = false;

        if ($pain && $pain['direction'] === 'turun') {
            $improving = true;
            $reasons[] = "Rata-rata nyeri turun dari {$pain['earlier']} menjadi {$pain['recent']}.";
        }

        if ($duration && $duration['direction'] === 'naik') {
            $improving = true;
            $reasons[] = "Rata-rata durasi latihan naik {$duration['change_percent']}%.";
        }

        if ($improving) {
            return $this->statusResult('membaik', $reasons);
        }

        $reasons[] = 'Tidak ada perubahan bermakna pada nyeri maupun durasi latihan.';

        return $this->statusResult('stabil', $reasons);
    }

    private function statusResult(string $key, array $reasons): array
    {
        return [
            'key' => $key,
            'label' => self::STATUS_LABELS[$key],
            'reasons' => $reasons,
        ];
    }

    /** Agregasi per minggu (Senin–Minggu), urut dari minggu terbaru. */
    private function weekly(array $sessions): array
    {
        $weeks = [];

        foreach ($sessions as $s) {
            $weekStart = $this->startOfDay($s['performed_at'])
                ->modify('-'.((int) $s['performed_at']->format('N') - 1).' days');
            $key = $weekStart->format('Y-m-d');

            $weeks[$key] ??= [
                'week_start' => $weekStart,
                'week_end' => $weekStart->modify('+6 days'),
                'sessions' => 0,
                'minutes' => 0,
                'pains' => [],
            ];

            $weeks[$key]['sessions']++;
            $weeks[$key]['minutes'] += $s['duration_minutes'];

            if ($s['pain_level'] !== null) {
                $weeks[$key]['pains'][] = $s['pain_level'];
            }
        }

        krsort($weeks);

        return array_values(array_map(function (array $w) {
            $w['average_pain'] = $w['pains'] ? round($this->average($w['pains']), 1) : null;
            unset($w['pains']);

            return $w;
        }, $weeks));
    }

    /** Ringkasan per jenis latihan, urut dari yang paling sering dilakukan. */
    private function byExercise(array $sessions): array
    {
        $groups = [];

        foreach ($sessions as $s) {
            $name = trim($s['exercise_name']);

            $groups[$name] ??= [
                'name' => $name,
                'sessions' => 0,
                'minutes' => 0,
                'pains' => [],
                'last_at' => $s['performed_at'],
            ];

            $groups[$name]['sessions']++;
            $groups[$name]['minutes'] += $s['duration_minutes'];
            $groups[$name]['last_at'] = max($groups[$name]['last_at'], $s['performed_at']);

            if ($s['pain_level'] !== null) {
                $groups[$name]['pains'][] = $s['pain_level'];
            }
        }

        $result = array_values(array_map(function (array $g) {
            $g['average_duration'] = round($g['minutes'] / $g['sessions'], 1);
            $g['average_pain'] = $g['pains'] ? round($this->average($g['pains']), 1) : null;
            unset($g['pains']);

            return $g;
        }, $groups));

        usort($result, fn ($a, $b) => [$b['sessions'], $b['minutes']] <=> [$a['sessions'], $a['minutes']]);

        return $result;
    }

    private function intensityDistribution(array $sessions): array
    {
        $distribution = ['Ringan' => 0, 'Sedang' => 0, 'Berat' => 0];

        foreach ($sessions as $s) {
            if (array_key_exists($s['intensity'], $distribution)) {
                $distribution[$s['intensity']]++;
            }
        }

        return $distribution;
    }

    /** Titik data per sesi untuk grafik tren (urut kronologis). */
    private function chartPoints(array $sessions): array
    {
        return [
            'labels' => array_map(fn ($s) => $s['performed_at']->format('d/m'), $sessions),
            'duration' => array_map(fn ($s) => (int) $s['duration_minutes'], $sessions),
            'pain' => array_map(fn ($s) => $s['pain_level'] !== null ? (int) $s['pain_level'] : null, $sessions),
        ];
    }

    /** Membagi sesi (sudah urut) menjadi paruh awal dan paruh akhir. */
    private function splitHalves(array $sessions): array
    {
        $half = intdiv(count($sessions), 2);

        // Jika ganjil, sesi tengah masuk paruh akhir (lebih mencerminkan kondisi terbaru)
        return [array_slice($sessions, 0, $half), array_slice($sessions, $half)];
    }

    private function painValues(array $sessions): array
    {
        return array_values(array_filter(
            array_column($sessions, 'pain_level'),
            fn ($p) => $p !== null
        ));
    }

    private function average(array $values): float
    {
        return $values ? array_sum($values) / count($values) : 0.0;
    }

    private function startOfDay(DateTimeInterface $date): DateTimeImmutable
    {
        return DateTimeImmutable::createFromInterface($date)->setTime(0, 0);
    }
}
