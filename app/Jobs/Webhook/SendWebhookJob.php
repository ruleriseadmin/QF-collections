<?php

namespace App\Jobs\Webhook;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWebhookJob implements ShouldQueue
{
    use Queueable;

    public $payload, $webhookUrl;

    /**
     * Create a new job instance.
     */
    public function __construct($payload, string $webhookUrl)
    {
        $this->payload = $payload;

        $this->webhookUrl = $webhookUrl;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            Http::post($this->webhookUrl, $this->payload);
        }catch(Exception $ex){
            Log::error('Error at SendWebhookJob: '.$ex->getMessage());
        }

        Log::info('Webhook sent', ['payload' => $this->payload]);
    }
}
