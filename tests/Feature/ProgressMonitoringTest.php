<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressMonitoringTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, string $username): User
    {
        return User::create([
            'name' => ucfirst($role).' Uji',
            'username' => $username,
            'email' => $username.'@picobot.test',
            'password' => 'password',
            'role' => $role,
        ]);
    }

    private function makePatientWithHistories(?User $user = null): Patient
    {
        $patient = Patient::create([
            'medical_record_number' => 'RM-UJI-'.uniqid(),
            'name' => 'Pasien Uji',
            'gender' => 'Laki-laki',
            'user_id' => $user?->id,
        ]);

        foreach ([[12, 20, 6], [9, 20, 6], [5, 25, 3], [1, 25, 3]] as [$daysAgo, $duration, $pain]) {
            $patient->exerciseHistories()->create([
                'exercise_name' => 'Latihan Berjalan',
                'intensity' => 'Sedang',
                'duration_minutes' => $duration,
                'pain_level' => $pain,
                'performed_at' => now()->subDays($daysAgo),
            ]);
        }

        return $patient;
    }

    public function test_admin_dapat_melihat_daftar_monitoring(): void
    {
        $admin = $this->makeUser('admin', 'admin');
        $patient = $this->makePatientWithHistories();

        $this->actingAs($admin)
            ->get(route('progress.index'))
            ->assertOk()
            ->assertSee($patient->name)
            ->assertSee('Membaik');
    }

    public function test_admin_dapat_melihat_detail_progres_dengan_berbagai_periode(): void
    {
        $admin = $this->makeUser('admin', 'admin');
        $patient = $this->makePatientWithHistories();

        foreach (['7', '30', '90', 'all', 'tidak-valid'] as $period) {
            $this->actingAs($admin)
                ->get(route('progress.show', [$patient->id, 'period' => $period]))
                ->assertOk()
                ->assertSee('Status Progres');
        }
    }

    public function test_pasien_diarahkan_ke_progres_miliknya(): void
    {
        $user = $this->makeUser('pasien', 'pasien');
        $this->makePatientWithHistories($user);

        $this->actingAs($user)
            ->get(route('progress.index'))
            ->assertRedirect(route('progress.mine'));

        $this->actingAs($user)
            ->get(route('progress.mine'))
            ->assertOk()
            ->assertSee('Progres Rehabilitasi Saya');
    }

    public function test_pasien_tidak_dapat_melihat_progres_pasien_lain(): void
    {
        $user = $this->makeUser('pasien', 'pasien');
        $other = $this->makePatientWithHistories();

        $this->actingAs($user)
            ->get(route('progress.show', $other->id))
            ->assertForbidden();
    }

    public function test_admin_tidak_dapat_membuka_halaman_progres_saya(): void
    {
        $admin = $this->makeUser('admin', 'admin');

        $this->actingAs($admin)
            ->get(route('progress.mine'))
            ->assertForbidden();
    }

    public function test_pasien_tanpa_data_pasien_mendapat_pesan(): void
    {
        $user = $this->makeUser('pasien', 'pasien');

        $this->actingAs($user)
            ->get(route('progress.mine'))
            ->assertOk()
            ->assertSee('belum terhubung dengan data pasien');
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get(route('progress.index'))->assertRedirect(route('login'));
    }
}
