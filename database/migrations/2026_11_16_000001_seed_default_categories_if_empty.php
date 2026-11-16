<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use App\Models\Category;

return new class extends Migration {
    /**
     * Run the migrations.
     * Ensure standard vocational categories exist in production/online server.
     */
    public function up(): void
    {
        $defaultCategories = [
            ['name' => 'Teknologi & Digital', 'icon' => 'fa-laptop-code', 'color' => '#2563eb', 'order' => 1],
            ['name' => 'Bahasa Asing', 'icon' => 'fa-language', 'color' => '#10b981', 'order' => 2],
            ['name' => 'Teknik Otomotif & Manufaktur', 'icon' => 'fa-car', 'color' => '#f59e0b', 'order' => 3],
            ['name' => 'Teknik Pengelasan (Welding)', 'icon' => 'fa-fire-burner', 'color' => '#ea580c', 'order' => 4],
            ['name' => 'Bisnis & Manajemen', 'icon' => 'fa-briefcase', 'color' => '#0d9488', 'order' => 5],
            ['name' => 'Perhotelan & Pariwisata', 'icon' => 'fa-hotel', 'color' => '#e11d48', 'order' => 6],
            ['name' => 'Tata Busana & Menjahit', 'icon' => 'fa-scissors', 'color' => '#8b5cf6', 'order' => 7],
            ['name' => 'Tata Boga & Kuliner', 'icon' => 'fa-utensils', 'color' => '#ec4899', 'order' => 8],
            ['name' => 'Kecantikan & Tata Rias', 'icon' => 'fa-wand-magic-sparkles', 'color' => '#d946ef', 'order' => 9],
            ['name' => 'Kesehatan & Caregiver', 'icon' => 'fa-heart-pulse', 'color' => '#06b6d4', 'order' => 10],
            ['name' => 'Konstruksi & Bangunan', 'icon' => 'fa-trowel-bricks', 'color' => '#64748b', 'order' => 11],
            ['name' => 'Elektronika & Listrik', 'icon' => 'fa-bolt', 'color' => '#eab308', 'order' => 12],
        ];

        foreach ($defaultCategories as $item) {
            Category::firstOrCreate(
                ['slug' => Str::slug($item['name'])],
                $item
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't delete categories to prevent foreign key errors with existing courses
    }
};
