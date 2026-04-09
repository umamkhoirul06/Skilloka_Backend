<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Lpk;
use App\Models\Category;
use App\Models\Course;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Roles
        Role::firstOrCreate(['name' => 'super-admin']);
        Role::firstOrCreate(['name' => 'lpk-admin']);
        Role::firstOrCreate(['name' => 'student']);

        // 2. Create Super Admin User
        $superAdmin = User::create([
            'name' => 'System Overlord',
            'email' => 'god@skilloka.com',
            'password' => Hash::make('password'), // Change in production!
            'phone' => '0800000000',
        ]);
        $superAdmin->assignRole('super-admin');

        // 3. Create Demo Tenants (LPKs)
        $tenants = [
            ['name' => 'LPK Merah Putih', 'domain' => 'merahputih', 'location' => 'Indramayu'],
            ['name' => 'Techno Skill Center', 'domain' => 'technoskill', 'location' => 'Bandung'],
            ['name' => 'Global English House', 'domain' => 'globalenglish', 'location' => 'Jakarta'],
        ];

        foreach ($tenants as $t) {
            $tenant = Tenant::create([
                'name' => $t['name'],
                'domain' => $t['domain'],
                'is_active' => true,
            ]);

            // Create LPK Profile
            $lpk = Lpk::create([
                'id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'name' => $t['name'],
                'address' => 'Jl. Contoh No. 123, ' . $t['location'],
                'is_verified' => true,
                'status' => 'active',
            ]);

            // Create LPK Admin
            $admin = User::create([
                'name' => 'Admin ' . $t['name'],
                'email' => 'admin@' . $t['domain'] . '.com',
                'password' => Hash::make('password'),
                'tenant_id' => $tenant->id,
            ]);
            $admin->assignRole('lpk-admin');
        }

        // 4. Create Categories
        $categories = ['Technology', 'Language', 'Vocational', 'Business'];
        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'icon' => 'default.png',
            ]);
        }
    }
}
