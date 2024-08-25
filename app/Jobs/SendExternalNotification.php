<?php

namespace App\Jobs;

use App\Clients\NotifyClient;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Exceptions\NotifyClientException;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendExternalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;

    /** Maximum number of attempts.*/
    public $tries = 5;

    /** Time between attempts (in seconds). */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(NotifyClient $notifyClient)
    {
        $this->client = $notifyClient;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->client->notify();
        } catch (NotifyClientException $e) {
            Log::error(sprintf('Failed to SendExternalNotification: %s', $e->getMessage()));
            $this->fail($e);
        }
    }
}
