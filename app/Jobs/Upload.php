<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Upload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $url,
        private readonly string $chunk,
        private readonly int $offset,
        private readonly int $nextOffset){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $nextOffset = Http::withHeaders([
            'Tus-Resumable'  => '1.0.0',
            'Upload-Offset'  => $this->offset,
            'Content-Length' => strlen($this->chunk),
            'Content-Type'   => 'application/offset+octet-stream',
        ])
            ->attach('file', $this->chunk)
            ->patch($this->url)->header('Upload-Offset');

        if ($nextOffset != $this->nextOffset){
            Log::error("failed to upload file invalid offsets");
            $this->fail("failed to upload");
        }

    }
}
