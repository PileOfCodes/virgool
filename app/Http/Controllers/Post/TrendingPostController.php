<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TrendingPostController extends Controller
{
    public function index()
    {
        $posts = collect(Redis::zrevrange('trending_posts', 0, 4))
        ->map(function ($post) {
            return json_decode($post,true);
        });

        return response([
            'posts' => $posts
        ], 200);
    }
}
