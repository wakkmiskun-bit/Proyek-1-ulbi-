<?php

namespace Tests\Feature\Auth;

use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $this->markTestSkipped('Mahasiswa tidak menggunakan verifikasi email.');
    }

    public function test_email_can_be_verified(): void
    {
        $this->markTestSkipped('Mahasiswa tidak menggunakan verifikasi email.');
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $this->markTestSkipped('Mahasiswa tidak menggunakan verifikasi email.');
    }
}
