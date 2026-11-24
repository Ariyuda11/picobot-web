<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Budi Santoso',
            'username' => 'budi_santoso',
            'email' => 'budi@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ], $overrides);
    }

    public function test_halaman_daftar_dapat_dibuka(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Buat Akun Pasien');
    }

    public function test_halaman_login_menampilkan_tautan_daftar(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee(route('register'));
    }

    public function test_pendaftaran_membuat_akun_pasien_lalu_dapat_login(): void
    {
        $this->post(route('register.process'), $this->validData())
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $user = User::where('username', 'budi_santoso')->first();
        $this->assertNotNull($user);
        $this->assertSame('pasien', $user->role);

        $this->post(route('login.process'), [
            'role' => 'pasien',
            'username' => 'budi_santoso',
            'password' => 'rahasia123',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_role_tidak_bisa_dimanipulasi_menjadi_admin(): void
    {
        $this->post(route('register.process'), $this->validData(['role' => 'admin']));

        $this->assertSame('pasien', User::where('username', 'budi_santoso')->value('role'));
    }

    public function test_username_dan_email_harus_unik(): void
    {
        $this->post(route('register.process'), $this->validData());

        $this->post(route('register.process'), $this->validData(['email' => 'lain@example.com']))
            ->assertSessionHasErrors('username');

        $this->post(route('register.process'), $this->validData(['username' => 'lain']))
            ->assertSessionHasErrors('email');
    }

    public function test_konfirmasi_password_harus_sama(): void
    {
        $this->post(route('register.process'), $this->validData(['password_confirmation' => 'beda12345']))
            ->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['username' => 'budi_santoso']);
    }

    public function test_pengguna_yang_sudah_login_tidak_dapat_membuka_halaman_daftar(): void
    {
        $user = User::create($this->validData() + ['role' => 'pasien']);

        $this->actingAs($user)->get(route('register'))->assertRedirect();
    }

    public function test_dropdown_akun_hanya_menampilkan_akun_yang_belum_terhubung(): void
    {
        $admin = User::create([
            'name' => 'Admin', 'username' => 'admin', 'email' => 'admin@example.com',
            'password' => 'password', 'role' => 'admin',
        ]);
        $linked = User::create([
            'name' => 'Sudah Terhubung', 'username' => 'terhubung', 'email' => 'a@example.com',
            'password' => 'password', 'role' => 'pasien',
        ]);
        User::create([
            'name' => 'Belum Terhubung', 'username' => 'bebas', 'email' => 'b@example.com',
            'password' => 'password', 'role' => 'pasien',
        ]);
        $patient = Patient::create([
            'medical_record_number' => 'RM-1', 'name' => 'P', 'gender' => 'Laki-laki', 'user_id' => $linked->id,
        ]);

        $this->actingAs($admin)->get(route('patients.create'))
            ->assertSee('Belum Terhubung')
            ->assertDontSee('Sudah Terhubung');

        // Di halaman edit, akun yang terhubung ke pasien ini tetap muncul
        $this->actingAs($admin)->get(route('patients.edit', $patient))
            ->assertSee('Belum Terhubung')
            ->assertSee('Sudah Terhubung');
    }
}
