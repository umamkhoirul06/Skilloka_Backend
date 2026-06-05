<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Course;

class FavoriteController extends Controller
{
    public function toggle($courseId)
{
    $user = auth()->user();

    $favorite = Favorite::where('user_id', $user->id)
        ->where('course_id', $courseId)
        ->first();

    if ($favorite) {
        $favorite->delete();

        return response()->json([
            'success' => true,
            'favorited' => false
        ]);
    }

    Favorite::create([
        'user_id' => $user->id,
        'course_id' => $courseId
    ]);

    return response()->json([
        'success' => true,
        'favorited' => true
    ]);
}
public function index()
{
    $favorites = Favorite::with('course')
        ->where('user_id', auth()->id())
        ->get();

    return response()->json([
        'success' => true,
        'data' => $favorites
    ]);
}
}
