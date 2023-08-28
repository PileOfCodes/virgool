<?php

namespace App\Http\Controllers\Comment;

use App\Events\ReplyCreatedEvent;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\PostCommentNotification;
use App\Notifications\PostReplyNotification;
use Illuminate\Http\Request;

class replyCommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required',
            'comment_id' => 'required|exists:comments,id'
        ]);

        $post->comments()->save(
            $reply = new Comment($request->only(['content','comment_id']))
        );

        $post->user->notify(new PostCommentNotification($post));
        Comment::find($request->comment_id)->user->notify(new PostReplyNotification($post));

        event(new ReplyCreatedEvent($reply));

        return response([
            'data' => $reply->load(['user','post','replies','parent'])
        ], 200);
    }
}
