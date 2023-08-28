<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AllUserPostsController extends Controller
{
    public function getAllPosts(Request $request)
    {
        return response(['data' => $request->user()->posts->map(function ($post) {
            return collect($post)->merge([
                'updated_at' => $post->updated_at->diffForHumans()]);
        }),
        'posts_count' => $request->user()->posts->count()
    ]);
    }
}
