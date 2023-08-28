<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LikedPostsController extends Controller
{
    public function index(Request $request)
    {
        $posts = $request->user()->likes()
        ->with('user')->withCount('likes')->simplePaginate(10);

        foreach ($posts as $post) {
            $postDate = Carbon::parse($post->created_at)->diffForHumans();
            foreach ($post->categories as $category) {
                $category = $category;
            }
        }

        return response([
            'posts' => $posts,
            'user' => $request->user()->loadCount(['likes','posts']),
            'postDate' => $postDate,
            'category' => $category
        ], 200);
    }
}
