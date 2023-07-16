<?php

namespace App\Jobs;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class Test implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    /**
     * Create a new job instance.
     */
    public function __construct(private $url, private $file_path, private $start, private $size){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (is_null($this->url)) $this->fail("Please Check Url First , Then Try Again");


        $fileHandler = fopen($this->file_path, 'rb');
        fseek($fileHandler, $this->start);
        $chunk = fread($fileHandler, $this->size);

//        $chunk = file_get_contents($this->file_path);
//        $fileHandlerWriter = fopen(public_path($this->index.'.mp4'), 'a+');
//        fwrite($fileHandlerWriter, $chunk);
//        fclose($fileHandlerWriter);
        try {
            Log::info(sprintf("Start Uploading From %s To %s With Size %s MB", $this->start, ftell($fileHandler), strlen($chunk) / 1024 / 1024));
//            Upload-Offset
            $client = new Client();
           $nextOffset = $client->patch($this->url, [
                'headers' => [
                    'Tus-Resumable'  => '1.0.0',
                    'Upload-Offset'  => $this->start,
                    'Content-Length' => strlen($chunk),
                    'Content-Type'   => 'application/offset+octet-stream',
                ],
                'body' => $chunk
            ]);


           Log::error('dddddddddddd');
            Log::error($nextOffset->getHeaders());
            Log::error($nextOffset->getBody());
            Log::error($nextOffset->getStatusCode());
            Log::error('ssssssssssssssss');

//            if (!$nextOffset)
//                $this->fail('no offset returned');

        }catch (Exception $exception){
            Log::error($exception->getMessage());
        }


    }
}
