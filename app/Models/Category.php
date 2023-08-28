<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
        'title' => ' عنوان ',
        'created_at' => '  تاریخ ساخت '

    ];

    protected $appends = [
        'show_category_date'
    ];
    
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function showCategoryDate() : Attribute {
        return new Attribute(
            get: fn() => verta($this->created_at)->formatDate()
        );
    }
}
