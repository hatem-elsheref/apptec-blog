<?php

namespace App\Services\Vimeo;

use App\Jobs\SendNotificationToAuthor;
use App\Jobs\VerifyUploadedFile;
use App\Models\Post;
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
            $response = Http::withToken($this->token)
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Accept', 'application/vnd.vimeo.*+json;version=3.4')
                ->post($this->url . '/me/videos', [
                    'upload' => [
                        'approach' => 'tus',
                        'size' => $size
                    ]
                ])->json();

            Log::error($response);
            return $response['upload']['upload_link'];
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            return null;
        }
    }

    public function upload(string $url, string $path, User $user, int $post)
    {
        $size = 1024 * 1024 * config('services.vimeo.size', 2);
        if (File::exists($path)){
            $fileHandler = fopen($path, 'rb');
            $start  = 0;
            $jobs = [];
            while (!feof($fileHandler)){
                $chunk = fread($fileHandler, $size);
                $nextOffset = ftell($fileHandler);
                $chunkSize = strlen($chunk);
                $jobs[] = new \App\Jobs\Upload($url, $path, $start, $chunkSize, $nextOffset);
                $start = $nextOffset;
            }

            if (!empty($jobs)){
                try {
                    Bus::batch($jobs)->then(function () use ($url, $user, $post){
                      VerifyUploadedFile::dispatch($url, $user, $post);
                    })->finally(function () use ($path) {
//                        File::delete($path);
                    })->catch(function (Batch $batch, Throwable $e) {
//                        $batch->cancel();
                        Log::error($e->getMessage());
                    })
                        ->allowFailures()
                        ->dispatch();

                } catch (Throwable $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }

    public static function verify($url, $user, $post)
    {
        try {
            $response = Http::withHeaders([
                'Tus-Resumable'  => '1.0.0',
                'Accept'         => 'application/offset+octet-stream',
            ])->head($url)->headers();
            if ($response['Upload-Length'] == $response['Upload-Offset']){
                SendNotificationToAuthor::dispatch($user, $post);
                Post::query()->where('id', $post)->update([
                    'is_published' => true
                ]);
            }
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            return null;
        }
    }
}
