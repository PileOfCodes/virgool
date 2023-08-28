<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $postFields = collect(Post::$FIELDS);
        $sort_by = $postFields->keys()
        ->contains($request->sort_by) ? $request->sort_by : 'id' ;

        $sort_type = $request->sort_type === 'asc' ? 'asc' : 'desc';

        $per_page = $request->per_page ?? 2;

        $headers = $postFields->transform(function ($item, $key) {
            return ['text' => $item, 'value' => $key];
        });

        if($request->search == null)
        {
            $posts = Post::with('user')
            ->withCount(['comments','likes'])
            ->orderBy($sort_by,$sort_type)
            ->orderBy(User::select('name')->whereColumn('users.id','posts.user_id'), $sort_type)
            ->paginate($per_page);
        }else {
            $posts = Post::with('user')
            ->withCount(['comments','likes'])
            ->orderBy($sort_by,$sort_type)
            ->orderBy(User::select('name')->whereColumn('users.id','posts.user_id'), $sort_type)
            ->where('title', 'LIKE',"%{$request->search}%")
            ->orWhere('content', 'LIKE',"%{$request->search}%")
            ->orWhereHas('user', function($q) use($request) {
                $q->where('name','LIKE',"%{$request->search}%");
            })
            ->paginate($per_page);
        }

        return response()->json([
            'posts' => $posts,
            'headers' => $headers
        ]);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response([
            "data" => "ok"
        ]);
    }
}
