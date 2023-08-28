<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class FollowingPostController extends Controller
{
    public function index(Request $request)
    {
        $following_user_id = $request->user()->follows()->pluck('id');
        return response([
            'posts' => Post::with('user')
            ->whereIn('user_id',$following_user_id)
            ->latest()
            ->take(3)
            ->get()
        ], 200);
    }
}
