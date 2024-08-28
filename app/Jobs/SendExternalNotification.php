<?php

namespace App\Jobs;

use App\Clients\LoggerClient;
use App\Clients\NotifyClient;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Exceptions\NotifyClientException;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendExternalNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $client;
    protected $loggerCliente;

    /** Maximum number of attempts.*/
    public $tries = 5;

    /** Time between attempts (in seconds). */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(NotifyClient $notifyClient, LoggerClient $loggerCliente)
    {
        $this->client = $notifyClient;
        $this->loggerCliente = $loggerCliente;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->client->notify();
        } catch (NotifyClientException $e) {
            $this->loggerCliente->log(sprintf('Failed to SendExternalNotification: %s', $e->getMessage()));
            $this->fail($e);
        }
    }
}
