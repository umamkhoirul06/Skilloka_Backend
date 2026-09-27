<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Lpk;
use App\Models\Category;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Roles (mendukung konvensi underscore dan dash)
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin_lpk', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'lpk-admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // 2. Create Super Admin User Utama
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@skilloka.com'],
            [
                'name' => 'Super Administrator Skilloka',
                'password' => Hash::make('password123'),
                'phone' => '081299990001',
                'status' => 'active',
            ]
        );
        $superAdmin->syncRoles(['super_admin', 'super-admin']);

        // Super Admin Alias (kompatibilitas seeder sebelumnya)
        $godAdmin = User::updateOrCreate(
            ['email' => 'god@skilloka.com'],
            [
                'name' => 'System Overlord',
                'password' => Hash::make('password'),
                'phone' => '080000000000',
                'status' => 'active',
            ]
        );
        $godAdmin->syncRoles(['super_admin', 'super-admin']);

        // 3. Create Demo Tenants (LPK Mitra Resmi)
        $tenants = [
            [
                'name' => 'LPK Merah Putih Indramayu',
                'domain' => 'merahputih',
                'email' => 'admin@merahputih.com',
                'location' => 'Kabupaten Indramayu',
                'address' => 'Jl. Jendral Sudirman No. 45, Karanganyar, Indramayu',
                'nib' => '9120001234567',
            ],
            [
                'name' => 'Techno Skill Center Indramayu',
                'domain' => 'technoskill',
                'email' => 'admin@technoskill.com',
                'location' => 'Jatibarang, Indramayu',
                'address' => 'Jl. Mayor Dasuki No. 88, Jatibarang, Indramayu',
                'nib' => '9120007654321',
            ],
            [
                'name' => 'Global English House Indramayu',
                'domain' => 'globalenglish',
                'email' => 'admin@globalenglish.com',
                'location' => 'Haurgeulis, Indramayu',
                'address' => 'Jl. Jenderal Ahmad Yani No. 12, Haurgeulis, Indramayu',
                'nib' => '9120009988776',
            ],
        ];

        foreach ($tenants as $t) {
            $tenant = Tenant::updateOrCreate(
                ['domain' => $t['domain']],
                [
                    'name' => $t['name'],
                    'is_active' => true,
                ]
            );

            // Create or update LPK Profile
            $lpk = Lpk::updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'name' => $t['name'],
                    'legal_name' => 'PT ' . $t['name'],
                    'nib' => $t['nib'],
                    'address' => $t['address'],
                    'description' => 'Lembaga Pelatihan Kerja resmi terakreditasi dan berizin NIB di ' . $t['location'] . '.',
                    'is_verified' => true,
                    'status' => 'active',
                    'status_verifikasi' => 'approved',
                ]
            );

            // Create LPK Admin User
            $admin = User::updateOrCreate(
                ['email' => $t['email']],
                [
                    'name' => 'Admin ' . $t['name'],
                    'password' => Hash::make('password123'),
                    'tenant_id' => $tenant->id,
                    'lpk_id' => $lpk->id,
                    'status' => 'active',
                ]
            );
            $admin->syncRoles(['admin_lpk', 'lpk-admin']);
        }

        // 4. Create Categories jika belum ada
        $categories = ['Teknologi & Digital', 'Bahasa Asing', 'Teknik Otomotif & Manufaktur', 'Bisnis & Manajemen', 'Perhotelan & Pariwisata'];
        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat)],
                [
                    'name' => $cat,
                    'icon' => 'default.png',
                ]
            );
        }
    }
}
