<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return $this->success($categories);
    }
}
