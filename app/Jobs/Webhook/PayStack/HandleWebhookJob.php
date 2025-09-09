<?php

namespace App\Jobs\Webhook\PayStack;

use App\Actions\Webhook\PayStack\HandleDVAEventAction;
use App\Actions\Webhook\PayStack\HandleFailedEventAction;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Actions\Webhook\PayStack\HandleSuccessEventAction;
use Illuminate\Support\Facades\Log;

class HandleWebhookJob implements ShouldQueue
{
    use Queueable;

    public $event, $payload, $model, $eventState, $statusState;

    public function __construct(string $event, $payload, $model, string $eventState = null, string $statusState = null)
    {
        $this->event = $event;

        $this->payload = $payload;

        $this->model = $model;

        $this->eventState = $eventState;

        $this->statusState = $statusState;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Webhook received', ['payload' => $this->payload]);

        match($this->event){
            'success_event' => (new HandleSuccessEventAction())->execute($this->model, $this->payload),
            'failed_event' => (new HandleFailedEventAction)->execute(
                tempTransaction: $this->model,
                payload: $this->payload,
                event: $this->eventState,
                status: $this->statusState
            ),
            'customer_identity_failed',
            'virtual_account_creation_failed',
            'virtual_account_creation_success',
                => (new HandleDVAEventAction)->execute(
                    customer: $this->model,
                    payload: $this->payload,
                    event: $this->event,
            ),
        };
    }
}
