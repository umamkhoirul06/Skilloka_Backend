<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends BaseController
{
    public function index()
    {
        // Ambil banner yang aktif dan terbaru
        $banners = Banner::where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'lpk_id' => $banner->lpk_id,
                    'title' => $banner->title,
                    // Pastikan kirim Absolute URL untuk Image.network() Flutter!
                    'image_url' => url('storage/' . $banner->image_path),
                ];
            });

        return $this->success($banners, 'Banners retrieved successfully');
    }
}