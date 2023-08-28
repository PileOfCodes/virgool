<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static $FIELDS = [
        'title' => ' عنوان ',
        'user.name' => ' نام نویسنده ',
        'created_at' => ' تاریخ ساخت ',
        'actions' => ''

    ];

    protected $appends = ['show_draft_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showDraftDate() : Attribute {
        return new Attribute(
            get: fn() => verta($this->created_at)->formatDate()
        );
    }

    
}
