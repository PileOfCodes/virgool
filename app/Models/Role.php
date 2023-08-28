<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static $FIELDS = [
     
        'name' => ' نام ',
        'created_at' => ' تاریخ ساخت '

    ];

    protected $appends = ['show_role_date'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function showRoleDate() : Attribute {
        return new Attribute(
            get: fn() => verta($this->created_at)->formatDate()
        );
    }
}
