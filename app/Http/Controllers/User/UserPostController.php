<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserPostController extends Controller
{
    public function index(User $user)
    {
        $posts = $user->posts()
        ->with(['user','categories'])
        ->withCount('likes')->simplePaginate(10);

        foreach ($posts as $post) {
            $postDate = Carbon::parse($post->created_at)->diffForHumans();
            foreach ($post->categories as $category) {
                $category = $category;
            }
        }
        return response([
            'posts' => $posts,
            'user' => $user->loadCount('posts'),
            'postDate' => $postDate,
            'category' => $category
        ], 200);
    }
}
