<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Lpk;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutVite;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SemuaHalamanBisaDiAksesTest extends TestCase
{
    // 🔥 Gunakan WithoutVite agar aman dari error "Manifest not found" saat CI/CD
    use RefreshDatabase, WithoutVite;

    protected function setUp(): void
    {
        parent::setUp();

        // 🔥 Reset cache permission bawaan Spatie (WAJIB)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 🔥 Jalankan migration untuk database testing
        $this->artisan('migrate');

        // 🔥 Pastikan role dasar sudah tersedia
        Role::firstOrCreate([
            'name' => 'admin-lpk',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);
    }

    private function admin()
    {
        // 🔥 1. Buat Tenant Dummy (Syarat aktif)
        $tenantId = (string) Str::uuid();
        $tenant = Tenant::forceCreate([
            'id' => $tenantId,
            'name' => 'Tenant Testing',
            'is_active' => true,
        ]);

        // 🔥 2. Buat LPK Dummy (Syarat agar lolos filter verifikasi)
        $lpkId = (string) Str::uuid();
        $lpk = Lpk::forceCreate([
            'id' => $lpkId,
            'tenant_id' => $tenantId,
            'name' => 'LPK Testing',
            'is_verified' => true,
            'status' => 'active',
            'status_verifikasi' => 'approved',
        ]);

        // 🔥 3. Buat User & Hubungkan dengan LPK dan Tenant di atas
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'tenant_id' => $tenantId,
            'lpk_id' => $lpkId,
            'status' => 'active',
        ]);

        $user->assignRole('admin-lpk');

        return $this->actingAs($user, 'web');
    }

    private function superAdmin()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        $user->assignRole('super-admin');

        return $this->actingAs($user, 'web');
    }

    /*
    |--------------------------------------------------------------------------
    | TEST HALAMAN PUBLIC
    |--------------------------------------------------------------------------
    */
    public function test_home_redirect(): void
    {
        $this->get('/')->assertRedirect(route('admin.login'));
    }

    public function test_login_page(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_register_page(): void
    {
        $this->get('/admin/register')->assertOk();
    }

    /*
    |--------------------------------------------------------------------------
    | TEST HALAMAN ADMIN LPK
    |--------------------------------------------------------------------------
    */
    public function test_admin_dashboard(): void
    {
        $this->admin()->get('/admin/dashboard')->assertOk();
    }

    public function test_admin_profile(): void
    {
        $this->admin()->get('/admin/profile')->assertOk();
    }

    public function test_courses_index(): void
    {
        $this->admin()->get('/admin/courses')->assertOk();
    }

    public function test_courses_create(): void
    {
        $this->admin()->get('/admin/courses/create')->assertOk();
    }

    public function test_students_index(): void
    {
        $this->admin()->get('/admin/students')->assertOk();
    }

    public function test_students_create(): void
    {
        $this->admin()->get('/admin/students/create')->assertOk();
    }

    public function test_bookings_index(): void
    {
        $this->admin()->get('/admin/bookings')->assertOk();
    }

    public function test_course_schedule_index(): void
    {
        $this->admin()->get('/admin/course-schedules')->assertOk();
    }

    public function test_course_schedule_create(): void
    {
        $this->admin()->get('/admin/course-schedules/create')->assertOk();
    }

    /*
    |--------------------------------------------------------------------------
    | TEST HALAMAN SUPER ADMIN
    |--------------------------------------------------------------------------
    */
    public function test_super_dashboard(): void
    {
        $this->superAdmin()->get('/super-admin/dashboard')->assertOk();
    }

    public function test_tenants(): void
    {
        $this->superAdmin()->get('/super-admin/tenants')->assertOk();
    }

    public function test_verifications(): void
    {
        $this->superAdmin()->get('/super-admin/verifications')->assertOk();
    }

    public function test_users(): void
    {
        $this->superAdmin()->get('/super-admin/users')->assertOk();
    }

    public function test_finance(): void
    {
        $this->superAdmin()->get('/super-admin/finance')->assertOk();
    }

    public function test_logs(): void
    {
        $this->superAdmin()->get('/super-admin/logs')->assertOk();
    }

    public function test_settings(): void
    {
        $this->superAdmin()->get('/super-admin/settings')->assertOk();
    }
}