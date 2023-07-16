<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\User;
use App\Services\Vimeo\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class UploadVideoToVimeo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Upload $uploadService;
    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $path,
        private readonly User $user,
        private readonly Post $post,
        private string|null $uploadUrl = null)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->uploadService = new Upload();

        if (File::exists($this->path)){
            $size = File::size($this->path);
            $this->create($size)->upload($size);
        }
    }

    private function create($size) :self
    {
        $this->uploadUrl = $this->uploadService->create($size, $this->post);
        return $this;
    }
    private function upload($fileSize) :void
    {
        if ($this->uploadUrl){
            $this->uploadService->upload($fileSize, $this->uploadUrl, $this->path, $this->user, $this->post);
        }else{
            Log::error("Failed To Upload File Because Url Returned From Create Api Is null");
        }
    }



}
