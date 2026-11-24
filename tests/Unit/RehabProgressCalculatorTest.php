<?php

namespace Tests\Unit;

use App\Services\RehabProgressCalculator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class RehabProgressCalculatorTest extends TestCase
{
    private DateTimeImmutable $now;

    protected function setUp(): void
    {
        parent::setUp();
        $this->now = new DateTimeImmutable('2026-11-24 22:00');
    }

    private function session(int $daysAgo, int $duration, ?int $pain, string $name = 'Latihan Berjalan', string $intensity = 'Sedang'): array
    {
        return [
            'performed_at' => $this->now->modify("-{$daysAgo} days")->setTime(9, 0),
            'exercise_name' => $name,
            'intensity' => $intensity,
            'duration_minutes' => $duration,
            'repetitions' => 5,
            'pain_level' => $pain,
        ];
    }

    private function calc(array $sessions, ?int $period = 30): array
    {
        return (new RehabProgressCalculator($this->now))->summarize($sessions, $period);
    }

    public function test_status_membaik_ketika_nyeri_turun_bermakna(): void
    {
        $r = $this->calc([
            $this->session(20, 20, 6),
            $this->session(16, 20, 6),
            $this->session(10, 25, 3),
            $this->session(2, 25, 3),
        ]);

        $this->assertSame('membaik', $r['status']['key']);
        $this->assertEquals(-3.0, $r['trend']['pain']['delta']);
        $this->assertSame('naik', $r['trend']['duration']['direction']);
    }

    public function test_status_perlu_perhatian_ketika_nyeri_naik(): void
    {
        $r = $this->calc([
            $this->session(20, 20, 2),
            $this->session(16, 20, 2),
            $this->session(10, 20, 5),
            $this->session(2, 20, 5),
        ]);

        $this->assertSame('perlu_perhatian', $r['status']['key']);
    }

    public function test_status_perlu_perhatian_ketika_tidak_aktif_lebih_dari_tujuh_hari(): void
    {
        $r = $this->calc([
            $this->session(25, 20, 3),
            $this->session(22, 20, 3),
            $this->session(18, 20, 3),
            $this->session(10, 20, 3),
        ]);

        $this->assertSame('perlu_perhatian', $r['status']['key']);
        $this->assertSame(10, $r['totals']['days_since_last']);
    }

    public function test_status_stabil_tanpa_perubahan_bermakna(): void
    {
        $r = $this->calc([
            $this->session(6, 20, 3),
            $this->session(5, 21, 3),
            $this->session(3, 20, 4),
            $this->session(1, 21, 3),
        ]);

        $this->assertSame('stabil', $r['status']['key']);
    }

    public function test_belum_cukup_data_jika_sesi_kurang_dari_empat(): void
    {
        $r = $this->calc([$this->session(1, 20, 3), $this->session(2, 20, 3)]);

        $this->assertSame('belum_cukup_data', $r['status']['key']);
        $this->assertNull($r['trend']['pain']);
        $this->assertNull($r['trend']['duration']);
    }

    public function test_tanpa_data_sama_sekali(): void
    {
        $r = $this->calc([]);

        $this->assertSame(0, $r['totals']['sessions']);
        $this->assertNull($r['totals']['last_session_at']);
        $this->assertSame('belum_cukup_data', $r['status']['key']);
    }

    public function test_filter_periode_tujuh_hari(): void
    {
        $r = $this->calc([
            $this->session(20, 20, 3),
            $this->session(6, 20, 3),
            $this->session(0, 20, 3),
        ], 7);

        $this->assertSame(2, $r['totals']['sessions']);
        $this->assertSame(7, $r['period']['length_days']);
    }

    public function test_sesi_di_masa_depan_diabaikan(): void
    {
        $future = $this->session(1, 20, 3);
        $future['performed_at'] = $this->now->modify('+2 days');

        $r = $this->calc([$future, $this->session(1, 20, 3)]);

        $this->assertSame(1, $r['totals']['sessions']);
        $this->assertSame(1, $r['totals']['days_since_last']);
    }

    public function test_rekap_per_jenis_latihan(): void
    {
        $r = $this->calc([
            $this->session(1, 20, 3, 'A'),
            $this->session(2, 30, null, 'B'),
            $this->session(8, 10, 5, 'A'),
        ]);

        $this->assertSame('A', $r['by_exercise'][0]['name']);
        $this->assertSame(2, $r['by_exercise'][0]['sessions']);
        $this->assertEquals(4.0, $r['by_exercise'][0]['average_pain']);
        $this->assertGreaterThan($r['weekly'][1]['week_start'], $r['weekly'][0]['week_start']);
    }
}
