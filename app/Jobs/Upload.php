<?php

namespace App\Jobs;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Upload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $url,
        private readonly string $path,
        private readonly int $offset,
        private readonly string $chunkSize){}
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            if (!File::exists($this->path)){
                Log::error("failed to upload file in Upload Job File => " . __FILE__);
                $this->fail("file not found");
            }

            if (is_null($this->url)){
                Log::error("No url founded so job failed to upload file in Upload Job File => " . __FILE__);
                $this->fail("Please Check Url First , Then Try Again");
            }

            $fileHandler = fopen($this->path, 'rb');

            fseek($fileHandler, $this->offset);

            $chunk = fread($fileHandler, $this->chunkSize);

            $nextOffset = ftell($fileHandler);

            Log::info(sprintf("Start Uploading From %s To %s With Size %s MB", $this->offset, $nextOffset, strlen($chunk) / 1024 / 1024));

            $client = new Client();

            $response = $client->patch($this->url, [
                'headers' => [
                    'Tus-Resumable'  => '1.0.0',
                    'Upload-Offset'  => $this->offset,
                    'Content-Length' => strlen($chunk),
                    'Content-Type'   => 'application/offset+octet-stream',
                ],
                'body' => $chunk
            ]);

            $headers = $response->getHeaders();
            if (!(isset($headers['Upload-Offset'][0]) && $headers['Upload-Offset'][0] == $nextOffset)){
                Log::error("no offset returned, or may be in valid calculations in offsets");
                Log::error(sprintf('%s != %s', $headers['Upload-Offset'][0] ?? 'null', $nextOffset));
                $this->fail('no offset returned, or may be in valid calculations in offsets');
            }

        }catch (Exception|GuzzleException $exception){
            Log::error($exception->getMessage());
            $this->fail($exception->getMessage());
        }

    }
}
