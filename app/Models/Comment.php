<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'comment', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean'
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }


    public function post() :BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
