<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    // Halaman utama mengarahkan pengguna ke halaman login
    public function test_halaman_utama_mengarahkan_ke_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_halaman_login_dapat_dibuka(): void
    {
        $this->get(route('login'))->assertOk();
    }
}
