<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SearchPostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::where('title', 'LIKE',"%{$request->q}%")
        ->orWhere('content', 'LIKE',"%{$request->q}%")
        ->with('user')
        ->withCount('likes')
        ->simplePaginate(10);

        if($posts)
        {
            foreach ($posts as $post) {
                $postDate = Carbon::parse($post->created_at)->diffForHumans();
                foreach ($post->categories as $category) {
                    $category = $category;
                }
            }
        }

        return response([
            'posts' => $posts,
            'postDate' => $postDate?? null,
            'category' => $category ?? null
        ], 200);
    }
}
