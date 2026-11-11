<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BannerController extends BaseController
{
    /**
     * GET /api/banners
     * Mengambil banner aktif dan terbaru (Dioptimasi dengan cache 5 menit).
     */
    public function index()
    {
        $banners = Cache::remember('api_active_banners', 300, function () {
            return Banner::where('is_active', true)
                ->latest()
                ->get()
                ->map(function ($banner) {
                    return [
                        'id' => $banner->id,
                        'lpk_id' => $banner->lpk_id,
                        'title' => $banner->title,
                        'image_url' => url('storage/' . $banner->image_path),
                    ];
                });
        });

        return $this->success($banners, 'Banners retrieved successfully');
    }
}
