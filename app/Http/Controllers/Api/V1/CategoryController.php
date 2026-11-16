<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    /**
     * GET /api/categories
     * Mengambil daftar kategori kursus (Dioptimasi dengan cache 5 menit).
     */
    public function index(Request $request)
    {
        $categories = Cache::remember('api_categories_list', 300, function () {
            $cats = Category::query()
                ->orderBy('order')
                ->orderBy('name')
                ->get();

            if ($cats->isEmpty()) {
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

                $cats = Category::query()->orderBy('order')->orderBy('name')->get();
            }

            return $cats;
        });

        return $this->success($categories);
    }
}
