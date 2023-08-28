<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'profile',
        'bio',
        'password',
        'email_on_follow',
        'email_on_like',
        'email_on_reply'
    ];

    public static $FIELDS = [
        'image' => ' تصویر کاربر ',
        'name' => ' نام ',
        'email' => ' ایمیل ',
        'username' => ' نام کاربری ',
        'posts_count' => '  تعداد پست ها ',
        'created_at' => ' تاریخ ساخت ',
        'actions' => ''

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_src',
        'is_followed',
        'show_user_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    protected function profileSrc() : Attribute
    {
        return new Attribute(
            get: fn() => '/profiles/' . $this->profile 
        );
    }

    public function drafts()
    {
        return $this->hasMany(Draft::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Post::class, 'bookmarks');
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'bookmarks');
    }

    public function follows()
    {
        return $this->belongsToMany(
            User::class, 
            'follows',
            'user_id',
            'following_id'
        );
    }

    public function followers()
    {
        return $this->belongsToMany(
            User::class, 
            'follows',
            'following_id',
            'user_id'
        );
    }

    public function isFollowed() : Attribute
    {
        return new Attribute(
            get: fn() => $this->followers()
            ->where('user_id', optional(request()->user())->id)
            ->exists()
        );
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roles)
    {
        foreach($roles as $role)
        {
            if($this->roles()->pluck('name')->contains($role)) return true;
        }
        return false;
    }

    public function allRolesId() : Attribute {
        return new Attribute(
            get: fn() => $this->roles->pluck('id')
        );
    }

    public function showUserDate() : Attribute {
        return new Attribute(
            get: fn() => verta($this->created_at)->formatDate()
        );
    }

}
