<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturePost extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = [
        'create_date'
    ];

    public function createDate() : Attribute {
        return new Attribute(
            get: fn() => Carbon::parse($this->post->created_at)->diffForHumans()
        );
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
