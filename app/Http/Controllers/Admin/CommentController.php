<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $commentFields = collect(Comment::$FIELDS);
        $sort_by = $commentFields->keys()
        ->contains($request->sort_by) ? $request->sort_by : 'id' ;

        $sort_type = $request->sort_type === 'asc' ? 'asc' : 'desc';

        $per_page = $request->per_page ?? 5;

        $headers = $commentFields->transform(function ($item, $key) {
            return ['text' => $item, 'value' => $key];
        });

        if($request->search == null)
        {
            $comments = Comment::with(['post','user','parent'])->orderBy($sort_by,$sort_type)->paginate($per_page);
        }else {
            $comments = Comment::with(['post','user','parent'])->orderBy($sort_by,$sort_type)
            ->where('content', 'LIKE',"%{$request->search}%")
            ->orWhereHas('user', function($q) use($request) {
                $q->where('name', 'LIKE', "%{$request->search}%");
            })
            ->orWhereHas('post', function($q) use($request) {
                $q->where('title', 'LIKE', "%{$request->search}%");
            })
            ->paginate($per_page);
        }

        return response()->json([
            'comments' => $comments,
            'headers' => $headers
        ]);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response([
            "data" => "ok"
        ], 200);
    }
}
