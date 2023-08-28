<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\CommentDateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['replies', 'user'];

    public static $FIELDS = [
        'content' => ' محتوا ',
        'post.title' => ' عنوان پست',
        'user.name' => '  کاربر نویسنده ',
        'created_at' => '  تاریخ ساخت '
    ];

    protected $appends = ['show_comment_date','show_readable_comment_date'];

    public static function booted()
    {
        static::saving(function($comment) {
            $comment->user_id = request()->user()->id;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function showCommentDate() : Attribute {
        return new Attribute(
            get: fn() => verta($this->created_at)->formatDate()
        );
    }

    public function showReadableCommentDate() : Attribute {
        return new Attribute(
            get: fn() => Carbon::parse($this->created_at)->diffForHumans()
        );
    }
}
