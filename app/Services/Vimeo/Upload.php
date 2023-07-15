<?php

namespace App\Services\Vimeo;

use App\Jobs\SendNotificationToAuthor;
use App\Models\User;
use Exception;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class Upload
{
    private string $url;
    private string $id;
    private string $token;
    private string $secret;
    public function __construct()
    {
        $this->url    = config('services.vimeo.url');
        $this->id     = config('services.vimeo.id');
        $this->token  = config('services.vimeo.token');
        $this->secret = config('services.vimeo.secret');
    }

    public function create(int $size) :string|null
    {
        try {
            return Http::withToken($this->token)
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Accept', 'application/vnd.vimeo.*+json;version=3.4')
                ->asForm()
                ->post($this->url . '/me/videos', [
                    'upload' => [
                        'approach' => 'tus',
                        'size' => $size
                    ]
                ])->json('upload.upload_link');
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            return null;
        }
    }

    public function upload(string $url, string $path, User $user, int $post)
    {
        $size = 1024 * 1024 * 2; //2MB
        if (File::exists($path)){
            $fileHandler = fopen($path, 'rb');
            $offset = 0;
            $jobs = [];
            while (!feof($fileHandler)){
                $chunk = fread($fileHandler, $size);
                $nextOffset = ftell($fileHandler);
                $jobs[] = new \App\Jobs\Upload($size, $chunk, $offset, $nextOffset);
                $offset = $nextOffset;
            }

            if (!empty($jobs)){
                $jobs[] = new SendNotificationToAuthor($user, $post);
                try {
                    Bus::batch($jobs)->finally(function () use ($path) {
                        File::delete($path);
                    })->catch(function (Batch $batch, Throwable $e) {
                        $batch->cancel();
                        Log::error($e->getMessage());
                    })->dispatch();
                } catch (Throwable $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }

    public function verify()
    {

    }
}
