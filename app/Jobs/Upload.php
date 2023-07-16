<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Upload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $tries = 3;
    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $url,
        private readonly string $path,
        private readonly int $offset,
        private readonly string $chunkSize,
        private readonly int $nextOffset){}
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!File::exists($this->path)){
            Log::error("file not found");
            $this->fail("failed to upload file");
        }

        $fileHandler = fopen($this->path, 'rb');
        fseek($fileHandler, $this->offset);
        $chunk = fread($fileHandler, $this->chunkSize);
        Log::error(sprintf("Start From %s And Read %s And Real Size Is %s", $this->offset, $this->chunkSize, strlen($chunk)));

        $nextOffset = Http::withHeaders([
            'Tus-Resumable'  => '1.0.0',
            'Upload-Offset'  => $this->offset,
            'Content-Length' => $this->chunkSize,
            'Content-Type'   => 'application/offset+octet-stream',
        ])
            ->attach('file_data', $chunk)
            ->patch($this->url)
            ->header('Upload-Offset');

        Log::error('######### Vimeo Return Offset ' . $nextOffset);
        Log::error('######### My Next Offset ' . $this->nextOffset);
        if (is_null($nextOffset) || $nextOffset != $this->nextOffset){
            Log::error("failed to upload file invalid offsets");
//            $this->fail("failed to upload");
        }

    }
}
