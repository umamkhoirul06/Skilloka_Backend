<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends BaseController
{
    /**
     * GET /api/categories
     * Mengambil daftar kategori kursus (Dioptimasi dengan cache 5 menit).
     */
    public function index(Request $request)
    {
        $categories = Cache::remember('api_categories_list', 300, function () {
            return Category::query()
                ->orderBy('order')
                ->orderBy('name')
                ->get();
        });

        return $this->success($categories);
    }
}
