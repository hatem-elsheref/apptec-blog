<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'image', 'video', 'user_id', 'is_published', 'new_video'];


    protected $casts = [
        'is_published' => 'boolean'
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }


    public function reacts() :HasMany
    {
        return $this->hasMany(React::class);
    }

    public function likes() :HasMany
    {
        return $this->hasMany(React::class)->where('is_like', 1);
    }

    public function disLikes() :HasMany
    {
        return $this->hasMany(React::class)->where('is_like', 0);
    }

    public function comments() :HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute() :string
    {
        return uploads($this->image);
    }
}
