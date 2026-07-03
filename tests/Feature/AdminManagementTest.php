<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_via_general_login_page(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'admin_test@gmail.com',
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'login' => 'admin_test@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_can_list_other_admins(): void
    {
        $admin1 = Admin::factory()->create(['email' => 'admin1@gmail.com']);
        $admin2 = Admin::factory()->create(['email' => 'admin2@gmail.com']);

        $response = $this->actingAs($admin1, 'admin')
            ->getJson('/admin/admins');

        $response->assertOk()
            ->assertJsonFragment(['email' => 'admin1@gmail.com'])
            ->assertJsonFragment(['email' => 'admin2@gmail.com']);
    }

    public function test_admin_can_create_new_admin(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->postJson('/admin/admins', [
                'nama' => 'New Admin',
                'email' => 'new_admin@gmail.com',
                'password' => 'password1234',
                'password_confirmation' => 'password1234',
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('admins', [
            'email' => 'new_admin@gmail.com',
            'nama' => 'New Admin',
        ]);
    }

    public function test_admin_can_update_another_admin(): void
    {
        $admin = Admin::factory()->create();
        $otherAdmin = Admin::factory()->create([
            'nama' => 'Old Name',
            'email' => 'old@gmail.com',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->putJson("/admin/admins/{$otherAdmin->id}", [
                'nama' => 'Updated Name',
                'email' => 'updated@gmail.com',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('admins', [
            'id' => $otherAdmin->id,
            'nama' => 'Updated Name',
            'email' => 'updated@gmail.com',
        ]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->deleteJson("/admin/admins/{$admin->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('admins', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_another_admin(): void
    {
        $admin = Admin::factory()->create();
        $otherAdmin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->deleteJson("/admin/admins/{$otherAdmin->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('admins', ['id' => $otherAdmin->id]);
    }

    public function test_non_admin_cannot_access_admin_crud(): void
    {
        $mahasiswa = Mahasiswa::factory()->create();

        $this->actingAs($mahasiswa, 'web')
            ->getJson('/admin/admins')
            ->assertStatus(401);

        $this->actingAs($mahasiswa, 'web')
            ->postJson('/admin/admins', [])
            ->assertStatus(401);
    }
}
