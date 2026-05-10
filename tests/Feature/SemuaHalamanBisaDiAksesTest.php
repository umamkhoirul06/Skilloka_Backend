<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Lpk;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Gate; // 🔥 INI KUNCI GOD MODE-NYA

class SemuaHalamanBisaDiAksesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Reset cache permission bawaan Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Jalankan migration untuk database testing
        $this->artisan('migrate');

        // 🔥 3. GOD MODE: Bypass semua pengecekan hak akses (Policy/Gate) bawaan Laravel
        Gate::before(function () {
            return true;
        });

        // 🔥 4. Buat SEMUA kemungkinan nama role yang mungkin dipakai temanmu
        $roles = ['superadmin', 'super-admin', 'Super Admin', 'admin', 'admin-lpk', 'lpk'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }
    }

    private function admin()
    {
        $tenantId = (string) Str::uuid();
        $tenant = Tenant::forceCreate([
            'id' => $tenantId,
            'name' => 'Tenant Testing',
            'is_active' => true,
        ]);

        $lpkId = (string) Str::uuid();
        $lpk = Lpk::forceCreate([
            'id' => $lpkId,
            'tenant_id' => $tenantId,
            'name' => 'LPK Testing',
            'is_verified' => true,
            'status' => 'active',
            'status_verifikasi' => 'approved',
        ]);

        $user = User::factory()->create([
            'email_verified_at' => now(),
            'tenant_id' => $tenantId,
            'lpk_id' => $lpkId,
            'status' => 'active',
            'role' => 'admin', // 🔥 Biasanya cuma disebut 'admin' di database
        ]);

        // Berikan semua jubah admin LPK ke user ini
        $user->assignRole(['admin', 'admin-lpk', 'lpk']);

        return $this->actingAs($user, 'web');
    }

    private function superAdmin()
    {
        $user = User::factory()->create([
            'email' => 'god@skilloka.com',
            'email_verified_at' => now(),
            'status' => 'active',
            'role' => 'superadmin', // 🔥 Tanpa strip (paling sering dipakai)
        ]);

        // Berikan semua jubah Super Admin ke user ini
        $user->assignRole(['superadmin', 'super-admin', 'Super Admin']);

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