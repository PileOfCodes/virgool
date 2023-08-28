<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomePostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user'])
        ->withCount('likes')
        ->orderByDesc('created_at')
        ->simplePaginate(10);

        foreach ($posts as $post) {
            $postDate = Carbon::parse($post->created_at)->diffForHumans();
            foreach ($post->categories as $category) {
                $category = $category;
            }
        }

        return response([
            'posts' => $posts,
            'postDate' => $postDate,
            'category' => $category
        ], 200);
    }
}
