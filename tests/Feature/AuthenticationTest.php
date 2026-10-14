<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_the_login_page(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk()->assertSee('Masuk ke KelasQu');
        $this->get(route('password.request'))->assertOk()->assertSee('Lupa kata sandi?');
    }

    public function test_registration_creates_a_regular_user_and_sends_verification_email(): void
    {
        Notification::fake();

        $response = $this->post(route('register.store'), [
            'name' => 'Nadia Belajar',
            'email' => 'nadia@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $this->assertAuthenticated();
        $this->get(route('verification.notice'))->assertOk()->assertSee('Periksa emailmu');
        $this->assertDatabaseHas('users', [
            'email' => 'nadia@example.com',
            'role' => 'user',
            'email_verified_at' => null,
        ]);
        $this->assertNotSame('Password123!', User::where('email', 'nadia@example.com')->firstOrFail()->password);
        Notification::assertSentTo(User::where('email', 'nadia@example.com')->firstOrFail(), VerifyEmail::class);
    }

    public function test_email_verification_unlocks_the_dashboard(): void
    {
        $user = User::factory()->unverified()->create(['role' => 'user']);
        $signedUrl = URL::temporarySignedRoute('verification.verify', now()->addMinutes(10), [
            'id' => $user->id,
            'hash' => sha1($user->getEmailForVerification()),
        ]);

        $this->actingAs($user)->get(route('dashboard'))->assertRedirect(route('verification.notice'));
        $this->get($signedUrl)->assertRedirect(route('dashboard'));
        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->get(route('dashboard'))->assertOk()->assertSee('Halo, '.$user->name);
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
        $this->get(route('user.materials'))->assertOk()->assertSee('Belum ada materi untuk dipelajari');
        $this->get(route('user.certificates'))->assertOk()->assertSee('Belum ada sertifikat');
        $this->get(route('profile.edit'))->assertOk()->assertSee('Profil saya');
        $this->get(route('admin.reports'))->assertForbidden();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.classes'))
            ->assertOk()
            ->assertSee('Kelola kelas');
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

    public function test_admin_can_manage_categories_courses_and_lessons(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->post(route('admin.categories.store'), [
            'name' => 'Desain Digital',
            'description' => 'Belajar desain digital.',
        ])->assertRedirect(route('admin.categories'));
        $this->get(route('admin.users'))->assertOk()->assertSee('Daftar pengguna');
        $this->get(route('admin.courses.create'))->assertOk()->assertSee('Buat kelas baru');

        $category = Category::where('name', 'Desain Digital')->firstOrFail();
        $this->post(route('admin.courses.store'), [
            'title' => 'Dasar UI Design',
            'description' => 'Belajar prinsip dasar desain antarmuka.',
            'category_id' => $category->id,
            'level' => 'Pemula',
            'duration_minutes' => 60,
            'status' => 'draft',
        ])->assertRedirect();

        $course = Course::where('title', 'Dasar UI Design')->firstOrFail();
        $lessonResponse = $this->post(route('admin.lessons.store', $course), [
            'title' => 'Prinsip visual',
            'content' => 'Kontras, hirarki, dan konsistensi.',
            'video_url' => '',
        ]);
        $lessonResponse->assertRedirect();
        $this->assertDatabaseHas('lessons', ['course_id' => $course->id, 'position' => 1]);

        $this->put(route('admin.courses.update', $course), [
            'title' => 'Dasar UI Design',
            'description' => 'Belajar prinsip dasar desain antarmuka.',
            'category_id' => $category->id,
            'level' => 'Pemula',
            'duration_minutes' => 60,
            'status' => 'published',
        ])->assertRedirect();

        $this->get(route('admin.courses.index'))->assertOk()->assertSee('Dasar UI Design');
        $this->get(route('admin.categories'))->assertOk()->assertSee('Desain Digital');
        $this->assertDatabaseHas('courses', ['id' => $course->id, 'status' => 'published']);
    }

    public function test_student_can_enroll_complete_lessons_and_get_a_certificate(): void
    {
        $student = User::factory()->create(['role' => 'user']);
        $category = Category::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        $course = Course::create([
            'category_id' => $category->id,
            'title' => 'Belajar Web',
            'slug' => 'belajar-web',
            'description' => 'Pengenalan web.',
            'level' => 'Pemula',
            'duration_minutes' => 30,
            'status' => 'published',
        ]);
        $firstLesson = $course->lessons()->create(['title' => 'HTML', 'content' => 'Struktur HTML.', 'position' => 1]);
        $secondLesson = $course->lessons()->create(['title' => 'CSS', 'content' => 'Tampilan CSS.', 'position' => 2]);

        $this->actingAs($student)
            ->get(route('user.catalog'))
            ->assertOk()
            ->assertSee('Belajar Web');

        $this->post(route('user.course.enroll', $course))
            ->assertRedirect(route('user.course.show', $course));

        $this->get(route('user.course.show', $course))->assertOk()->assertSee('HTML');
        $this->get(route('user.lesson.show', [$course, $firstLesson]))->assertOk()->assertSee('Struktur HTML.');
        $this->post(route('user.lesson.complete', [$course, $firstLesson]))->assertRedirect();
        $this->assertSame(0, Certificate::count());
        $this->get(route('user.classes'))->assertOk()->assertSee('Belajar Web');
        $this->get(route('user.materials'))->assertOk()->assertSee('CSS');

        $this->post(route('user.lesson.complete', [$course, $secondLesson]))->assertRedirect();
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
        $this->assertSame(1, Certificate::count());
        $this->assertNotNull(Enrollment::where('user_id', $student->id)->firstOrFail()->completed_at);
        $certificate = Certificate::firstOrFail();

        $this->get(route('user.certificates'))->assertOk()->assertSee('Belajar Web');
        $this->get(route('user.certificate.show', $certificate))->assertOk()->assertSee($student->name);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->put(route('admin.lessons.update', [$course, $firstLesson]), [
            'title' => 'HTML revisi',
            'content' => 'Struktur HTML yang diperbarui.',
            'video_url' => '',
        ])->assertRedirect();

        $this->assertSame(0, Certificate::count());
        $this->assertNull(Enrollment::where('user_id', $student->id)->firstOrFail()->completed_at);
        $this->assertSame(0, $student->lessonProgress()->count());
    }

    public function test_student_cannot_open_another_students_lesson_or_certificate(): void
    {
        $enrolledStudent = User::factory()->create(['role' => 'user']);
        $otherStudent = User::factory()->create(['role' => 'user']);
        $course = Course::create([
            'title' => 'Kelas Privat',
            'slug' => 'kelas-privat',
            'description' => 'Konten.',
            'level' => 'Pemula',
            'duration_minutes' => 20,
            'status' => 'published',
        ]);
        $lesson = $course->lessons()->create(['title' => 'Pertama', 'content' => 'Rahasia.', 'position' => 1]);
        $enrollment = Enrollment::create([
            'user_id' => $enrolledStudent->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);
        $certificate = Certificate::create([
            'enrollment_id' => $enrollment->id,
            'code' => (string) \Illuminate\Support\Str::uuid(),
            'issued_at' => now(),
        ]);

        $this->actingAs($otherStudent)
            ->get(route('user.lesson.show', [$course, $lesson]))
            ->assertForbidden();
        $this->get(route('user.certificate.show', $certificate))->assertNotFound();
    }

    public function test_admin_can_change_user_access_but_cannot_remove_last_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $student), [
                'role' => 'user',
                'is_active' => '0',
            ])->assertRedirect(route('admin.users'));
        $this->assertDatabaseHas('users', ['id' => $student->id, 'is_active' => 0]);

        $this->put(route('admin.users.update', $admin), [
            'role' => 'user',
            'is_active' => '0',
        ])->assertSessionHasErrors('account');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin', 'is_active' => 1]);
    }

    public function test_inactive_users_cannot_sign_in(): void
    {
        $user = User::factory()->create([
            'email' => 'disabled@example.com',
            'password' => 'Password123!',
            'is_active' => false,
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_password_reset_link_can_be_requested_and_used(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'reset@example.com', 'password' => 'OldPassword123!']);

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
        $notification = Notification::sent($user, ResetPassword::class)->first();
        $this->get(route('password.reset', ['token' => $notification->token, 'email' => $user->email]))
            ->assertOk()
            ->assertSee('Atur ulang kata sandi');

        $this->post(route('password.update'), [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertRedirect(route('login'));

        $this->assertTrue(password_verify('NewPassword123!', $user->fresh()->password));
    }
}
