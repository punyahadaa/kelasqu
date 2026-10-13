<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_the_login_page(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk()->assertSee('Masuk ke KelasQu');
    }

    public function test_registration_creates_a_regular_user_and_signs_them_in(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Nadia Belajar',
            'email' => 'nadia@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'nadia@example.com',
            'role' => 'user',
        ]);
        $this->assertNotSame('Password123!', User::where('email', 'nadia@example.com')->firstOrFail()->password);
    }

    public function test_user_can_sign_in_and_open_their_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'student@example.com',
            'password' => 'Password123!',
            'role' => 'user',
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))->assertOk()->assertSee('Halo, '.$user->name);
    }

    public function test_admin_dashboard_is_limited_to_admin_users(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('admin.users'))
            ->assertForbidden();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Admin');
    }

    public function test_navigation_pages_are_separated_by_user_role(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('user.classes'))
            ->assertOk()
            ->assertSee('Kelas Saya');
        $this->get(route('user.materials'))->assertOk()->assertSee('Materi belum tersedia');
        $this->get(route('user.certificates'))->assertOk()->assertSee('Belum ada sertifikat');
        $this->get(route('profile.edit'))->assertOk()->assertSee('Profil saya');
        $this->get(route('admin.reports'))->assertForbidden();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.classes'))
            ->assertOk()
            ->assertSee('Kelas yang tersedia');
        $this->get(route('admin.categories'))->assertOk()->assertSee('Kategori kelas');
        $this->get(route('admin.reports'))->assertOk()->assertSee('Laporan KelasQu');
        $this->get(route('admin.settings'))->assertOk()->assertSee('Konfigurasi saat ini');
        $this->get(route('user.classes'))->assertForbidden();
    }

    public function test_user_can_update_profile_and_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Awal',
            'email' => 'awal@example.com',
            'password' => 'Password123!',
        ]);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Nama Baru',
                'email' => 'baru@example.com',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('status', 'Profil berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'email' => 'baru@example.com',
        ]);
        $this->assertTrue(password_verify('NewPassword123!', $user->fresh()->password));
    }
}
