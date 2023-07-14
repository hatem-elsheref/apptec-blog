<?php

namespace App\Observers;

use Illuminate\Support\Facades\File;

class UserObserver
{
    public function deleted($user)
    {
        $posts = $user->posts;

        foreach ($posts as $post){

            $path = storage_path('app' . DIRECTORY_SEPARATOR . $post->image);

            if (File::exists($path))
                File::delete($path);

            $post->delete();
        }
    }
}
