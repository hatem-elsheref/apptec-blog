<?php

namespace App\Services\Vimeo;

use App\Jobs\SendNotificationToAuthor;
use App\Jobs\VerifyUploadedFile;
use App\Models\Post;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class Upload
{
    private string $url;
    private string $token;
    private string $id;
    private string $secret;
    private Client $client;
    public function __construct()
    {
        $this->url    = config('services.vimeo.url');
        $this->id     = config('services.vimeo.id');
        $this->token  = config('services.vimeo.token');
        $this->secret = config('services.vimeo.secret');
        $this->client = new Client();
    }

    public function create(int $size, Post $post) :string|null
    {
        try {
            $response = $this->client->post(sprintf("%s/%s", $this->url, 'me/videos'), [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/vnd.vimeo.*+json;version=3.4',
                    'Authorization' => sprintf('Bearer %s', $this->token)
                ],
                'body' => json_encode([
                    'upload'      => ['approach' => 'tus', 'size' => $size],
                    'name'        => Str::limit($post->title, 20) . '-' . $post->created_at->format('d-m-Y H:i'),
                    'description' => $post->title
                ])
            ]);


            $response = json_decode($response->getBody()->getContents(), true);
            if (isset($response['player_embed_url'], $response['upload']['upload_link']) && $response['upload']['approach'] === 'tus'){
                if (is_null($post->video))
                    $post->update(['video' => $response['player_embed_url'], 'new_video' => $response['player_embed_url']]);
                else
                    $post->update(['new_video' => $response['player_embed_url']]);
                return $response['upload']['upload_link'];
            }
            throw new Exception("Failed To Create Video");
        }catch (Exception|GuzzleException $exception){
            Log::error($exception->getMessage());
            return null;
        }
    }

    public function upload(int $fileSize, string $url, string $path, User $user, Post $post) :void
    {

        if (File::exists($path)){
            $chunkSize = 1024 * 1024 *  config('services.vimeo.size', 5); //5MB

            $offsets = range(0, $fileSize, $chunkSize);

            $jobs = [];
            foreach ($offsets as $offset){
                $jobs[] = new \App\Jobs\Upload($url, $path, $offset, $chunkSize);
            }

            if (!empty($jobs)){
                try {
                    Bus::batch($jobs)->then(function () use ($url, $user, $post){
                      VerifyUploadedFile::dispatch($url, $user, $post);
                    })->finally(function (Batch $batch) use ($path, $user, $post) {
                        $batch->hasFailures()
                            ? SendNotificationToAuthor::dispatch($user, $post, false)
                            : File::delete($path);
                    })->catch(function (Batch $batch, Throwable $e) {
                        Log::error("Failed In Bus Batch " . $batch->id);
                        Log::error("Bus Batch Progress Is " . $batch->progress());
                        Log::error($e->getMessage());
                    })->allowFailures()->dispatch();

                } catch (Throwable $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }

    public static function verify($url, $user, $post) :void
    {
        try {
            $client = new Client();

            $response = $client->head($url, [
                'headers' => [
                    'Tus-Resumable'  => '1.0.0',
                    'Accept'         => 'application/offset+octet-stream',
                ]
            ])->getHeaders();


            if (isset($response['Upload-Length'], $response['Upload-Offset']) && $response['Upload-Length'] == $response['Upload-Offset']){
                SendNotificationToAuthor::dispatch($user, $post, true);
                Post::query()->where('id', $post->id)->update([
                    'is_published' => true,
                    'video'        => $post->new_video
                ]);
                // if post->video is not null and valid url send http request to delete it
            }
        }catch (Exception|GuzzleException $exception){
            Log::error("Failed To Verify Uploaded File " . $post->video);
            Log::error($exception->getMessage());
        }
    }
}
