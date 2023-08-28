<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    use Sluggable;

    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static $FIELDS = [
        'image' => ' تصویر پست ',
        'title' => ' عنوان ',
        'description' => ' توضیحات ',
        'user.name' => ' نام کاربر '

    ];

    protected $appends = [
        'is_bookmarked',
        'is_liked',
        'is_image',
        'categories_title'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function parentComments()
    {
        return $this->comments()->whereNull('comment_id');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public static function booted()
    {
        static::saving(function ($post) {
            $post->short_link = Str::random(7);
        });
    }

    public function isBookmarked() : Attribute
    {
        return new Attribute(
            get: fn() => $this->bookmarks()
            ->where('user_id',optional(request()->user())->id)->exists()
        );
    }

    public function isLiked() : Attribute
    {
        return new Attribute(
            get: fn() => $this->likes()
            ->where('user_id',optional(request()->user())->id)->exists()
        );
    }

    public function isImage() : Attribute
    {
        return new Attribute(
            get: fn() => '/posts/' . $this->image
        );
    }

    public function categoriesTitle() : Attribute {
        return new Attribute(
            get: fn() => $this->categories->pluck('title')
        );
    }

    public function FeaturePost()
    {
        return $this->hasOne(FeaturePost::class);
    }
}
