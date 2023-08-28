<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostCategoriesController extends Controller
{
    public function index(Category $category)
    {
        $posts = $category->posts()->with('user')->withCount('likes')->simplePaginate(10);
        foreach ($posts as $post) {
            $newDate = Carbon::parse($post->created_at)->diffForHumans();
        }
        return response([
            'posts' => $posts,
            'postDate' => $newDate,
            'category' => $category->loadCount('posts')
        ]);
    }
}
