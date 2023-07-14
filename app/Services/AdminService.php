<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Models\React;
use App\Models\User;

class AdminService
{
    public function getStatistics() :array
    {
        return  [
            'users'    => User::query()->count(),
            'posts'    => Post::query()->count(),
            'comments' => Comment::query()->count(),
            'reacts'   => React::query()->count(),
        ];
    }
}

