<?php

namespace Database\Seeders;

use App\Models\ExerciseHistory;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['username' => '@admin123'],
            [
                'name' => 'Administrator PICOBOT',
                'email' => 'admin@picobot.test',
                'password' => '12345',
                'role' => 'admin',
            ]
        );

        $pasienUser = User::updateOrCreate(
            ['username' => 'pasien'],
            [
                'name' => 'Pasien Contoh',
                'email' => 'pasien@picobot.test',
                'password' => 'password',
                'role' => 'pasien',
            ]
        );

        $patient = Patient::updateOrCreate(
            ['medical_record_number' => 'RM-2026-001'],
            [
                'name' => 'Pasien Contoh',
                'gender' => 'Laki-laki',
                'birth_date' => '1975-04-12',
                'address' => 'Surabaya, Jawa Timur',
                'phone' => '081234567890',
                'user_id' => $pasienUser->id,
            ]
        );

        if ($patient->exerciseHistories()->doesntExist()) {
            $samples = [
                [
                    'exercise_name' => 'Latihan Rentang Gerak Sendi',
                    'intensity' => 'Ringan',
                    'duration_minutes' => 20,
                    'repetitions' => 10,
                    'pain_level' => 2,
                    'notes' => 'Latihan berjalan lancar tanpa kendala berarti.',
                    'performed_at' => now()->subDays(3)->setTime(9, 0),
                ],
                [
                    'exercise_name' => 'Latihan Keseimbangan',
                    'intensity' => 'Sedang',
                    'duration_minutes' => 30,
                    'repetitions' => 8,
                    'pain_level' => 4,
                    'notes' => 'Pasien masih membutuhkan pendampingan saat berdiri.',
                    'performed_at' => now()->subDays(2)->setTime(10, 30),
                ],
                [
                    'exercise_name' => 'Latihan Berjalan',
                    'intensity' => 'Sedang',
                    'duration_minutes' => 25,
                    'repetitions' => 6,
                    'pain_level' => 3,
                    'notes' => 'Jarak tempuh latihan berjalan bertambah dibanding sesi sebelumnya.',
                    'performed_at' => now()->subDay()->setTime(8, 15),
                ],
            ];

            foreach ($samples as $sample) {
                $patient->exerciseHistories()->create($sample + [
                    'recorded_by' => $admin->id,
                ]);
            }
        }
    }
}
