<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected function avatar(): Attribute
    {
        return Attribute::make(get: function ($value) {

            return $value ? '/storage/avatars/' . $value : '/fallback-avatar.jpg';
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function followers()
    {
        //<nn>
        // Represent that the user can have MANY FOLLOWERS
        //</nn>
        return $this->hasMany(Follow::class, 'followeduser', 'id');
    }

    public function followingTheseUsers()
    {
        //<nn>
        // Represent that the user able to follow MANY other users
        //</nn>
        return $this->hasMany(Follow::class, 'user_id', 'id');
    }

    public function posts()
    {
        //<nn>
        // Repesents that user can have MANY posts.
        //</nn>
        return $this->hasMany(Post::class, 'user_id');
    }

    public function feedPosts()
    {
        //<nn>
        // Represetnt the POSTS of the FOLOWED users. 
        //</nn>
        return $this->hasManyThrough(Post::class, Follow::class, 'user_id', 'user_id', 'id', 'followeduser');
    }
}
