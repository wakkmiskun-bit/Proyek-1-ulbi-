<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::factory()->create([
            'email' => 'admin1@gmail.com',
        ]);
    }

    private function createMahasiswa(): Mahasiswa
    {
        return Mahasiswa::factory()->create([
            'phone' => '6281234567890',
        ]);
    }

    public function test_admin_can_list_real_mahasiswa_from_database(): void
    {
        $admin = $this->createAdmin();
        $mhs = $this->createMahasiswa();
        Task::factory()->for($mhs, 'mahasiswa')->create(['title' => 'Tugas Real']);

        $this->actingAs($admin, 'admin')
            ->getJson('/admin/mahasiswas')
            ->assertOk()
            ->assertJsonFragment(['nim' => $mhs->nim])
            ->assertJsonFragment(['tasks_count' => 1]);
    }

    public function test_lihat_data_returns_mahasiswa_and_tasks(): void
    {
        $admin = $this->createAdmin();
        $mhs = $this->createMahasiswa();
        Task::factory()->for($mhs, 'mahasiswa')->create(['title' => 'Laporan PKL']);

        $this->actingAs($admin, 'admin')
            ->getJson("/admin/mahasiswas/{$mhs->id}")
            ->assertOk()
            ->assertJsonFragment(['name' => $mhs->name])
            ->assertJsonFragment(['title' => 'Laporan PKL']);
    }

    public function test_edit_mahasiswa_updates_database(): void
    {
        $admin = $this->createAdmin();
        $mhs = $this->createMahasiswa();

        $this->actingAs($admin, 'admin')
            ->putJson("/admin/mahasiswas/{$mhs->id}", [
                'name' => 'Nama Baru',
                'phone' => '628111222333',
            ])
            ->assertOk()
            ->assertJsonFragment(['name' => 'Nama Baru']);

        $this->assertDatabaseHas('mahasiswas', [
            'id' => $mhs->id,
            'nama' => 'Nama Baru',
            'phone' => '628111222333',
        ]);
    }

    public function test_dashboard_button_route_renders_user_board(): void
    {
        $admin = $this->createAdmin();
        $mhs = $this->createMahasiswa();

        $this->actingAs($admin, 'admin')
            ->get("/admin/mahasiswas/{$mhs->id}/board")
            ->assertOk()
            ->assertSee($mhs->name);
    }
}
