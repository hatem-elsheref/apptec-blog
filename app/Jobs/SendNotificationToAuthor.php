<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\User;
use App\Notifications\VideoUploadedSuccessfully;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationToAuthor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly User $user, private readonly int $post){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($post = Post::query()->find($this->post))
            $this->user->notify(new VideoUploadedSuccessfully($post));
    }
}
