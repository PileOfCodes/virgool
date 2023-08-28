<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ShowPostController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Post $post)
    {
        $related_posts = Post::query()->with('user')
        ->where('id', '!=', $post->id)
        ->whereHas('categories', function($query) use($post) {
            $query->whereIn('categories.id', $post->categories->pluck('id'));
        })->inRandomOrder()->take(3)->get();

        Redis::zincrby('trending_posts', 1, json_encode([
            'title' => $post->title,
            'slug' => $post->slug,
            'user_name' => $post->user->name,
            'user_profile_src' => $post->user->profile_src
        ]));

        $post->load(['user','categories', 'parentComments'])
            ->loadCount(['comments','likes']);
        $post->user->append('is_followed');
            
        return response([
            'post' => $post,
            'related_posts' => $related_posts,
            'dateTime' => Carbon::parse($post->created_at)->diffForHumans()
        ], 200);
    }
}
