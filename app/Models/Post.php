<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use Searchable;

    protected  $fillable = [
        'post_title',
        'post_body',
        'user_id'
    ];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function toSearchableArray()
    {
        return [
            'post_title' => $this->post_title,
            'post_body' => $this->post_body,
        ];
    }
}
