<?php

namespace App\Services;

use App\Models\ExerciseHistory;
use App\Models\Patient;
use Illuminate\Support\Collection;

/**
 * Penghubung antara model Eloquent dan RehabProgressCalculator.
 */
class RehabProgressService
{
    /**
     * Ringkasan progres satu pasien untuk periode tertentu.
     */
    public function forPatient(Patient $patient, ?int $periodDays = 30): array
    {
        $histories = $patient->relationLoaded('exerciseHistories')
            ? $patient->exerciseHistories
            : $patient->exerciseHistories()->get();

        return $this->summarize($histories, $periodDays);
    }

    /**
     * @param  Collection<int, ExerciseHistory>  $histories
     */
    public function summarize(Collection $histories, ?int $periodDays = 30): array
    {
        $sessions = $histories->map(fn (ExerciseHistory $history) => [
            'performed_at' => $history->performed_at,
            'exercise_name' => $history->exercise_name,
            'intensity' => $history->intensity,
            'duration_minutes' => (int) $history->duration_minutes,
            'repetitions' => $history->repetitions,
            'pain_level' => $history->pain_level !== null ? (int) $history->pain_level : null,
        ])->all();

        return (new RehabProgressCalculator(now()))->summarize($sessions, $periodDays);
    }

    /**
     * Membaca parameter periode dari request. Default 30 hari.
     * Nilai "all" berarti seluruh riwayat (null).
     */
    public static function parsePeriod(?string $value): ?int
    {
        if ($value === 'all') {
            return null;
        }

        $days = (int) $value;

        return in_array($days, RehabProgressCalculator::PERIODS, true) ? $days : 30;
    }
}
