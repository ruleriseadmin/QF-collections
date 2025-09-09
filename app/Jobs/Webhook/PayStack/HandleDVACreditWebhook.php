<?php

namespace App\Jobs\Webhook\PayStack;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Customer\CustomerVirtualAccount;
use App\Actions\Webhook\PayStack\HandleDVAEventAction;

class HandleDVACreditWebhook implements ShouldQueue
{
    use Queueable;

    public $payload;

    /**
     * Create a new job instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Webhook received', ['payload' => $this->payload]);

        $virtualAccount = CustomerVirtualAccount::where('account_number', $this->payload['data']['authorization']['receiver_bank_account_number'])->first();

        if ( ! $virtualAccount )return;

        (new HandleDVAEventAction)->execute(
            customer: $virtualAccount->customer,
            event: 'virtual_account_fund',
            payload: $this->payload,
            meta: [
                'accessGateway' => $virtualAccount->accessGateway,
                'virtualAccount' => $virtualAccount,
            ],
        );
    }
}
