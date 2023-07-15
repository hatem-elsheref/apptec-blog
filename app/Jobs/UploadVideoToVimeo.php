<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Vimeo\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class UploadVideoToVimeo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Upload $uploadService;
    private string|null $uploadUrl;
    /**
     * Create a new job instance.
     */
    public function __construct(private readonly string $path, private readonly User $user, private readonly int $post)
    {
        $this->uploadUrl = null;
        $this->uploadService = new Upload();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (File::exists($this->path)){
            $size = File::size($this->path);
            $this->create($size)->upload();
        }
    }

    private function create($size) :self
    {
        $this->uploadUrl = $this->uploadService->create($size);
        return $this;
    }
    private function upload() :self
    {
        if ($this->uploadUrl){
            $this->uploadService->upload($this->uploadUrl, $this->path, $this->user, $this->post);
        }
        return $this;
    }



}
