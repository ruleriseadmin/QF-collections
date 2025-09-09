<?php

use App\Enums\TransactionEnum;
use App\Models\CardTokenization;
use App\Models\Customer\Customer;
use App\Models\Webhook\WebhookLog;
use Illuminate\Support\Facades\Bus;
use App\Jobs\Webhook\SendWebhookJob;
use App\Models\Gateway\AccessGateway;
use App\Models\VirtualAccountProcess;
use Illuminate\Support\Facades\Queue;
use App\Models\Gateway\GatewayTransaction;
use App\Jobs\Webhook\PayStack\HandleWebhookJob;
use App\Http\Controllers\Webhook\WebhookController;
use App\Actions\AccessGateway\UpdateWebhookUrlAction;
use App\Actions\AccessGateway\GenerateAccessGatewayAction;
use App\Jobs\Webhook\PayStack\HandleDVACreditWebhook;
use App\Models\Customer\CustomerVirtualAccount;

describe('PayStackWebhookTest', function () {
   it('Successful card tokenization', function () {
     Queue::fake();

    (new GenerateAccessGatewayAction)->execute();

    $accessGateway = AccessGateway::first();

    (new UpdateWebhookUrlAction)->execute($accessGateway, 'https://www.google.com');

    $customer = Customer::factory()->create();

    $transaction = $accessGateway->tempTransactions()->create([
        'customer_id' => $customer->id,
        'type' => TransactionEnum::TYPE['card_tokenization'],
        'provider' => TransactionEnum::PROVIDERS['paystack'],
        'status' => TransactionEnum::STATUS['pending'],
        'reference' => 'ref',
        'amount' => 5000,
    ]);

        $payload = '{
                "event": "charge.success",
                "data": {
                    "id": 4378045029,
                    "domain": "test",
                    "status": "success",
                    "reference": "ref",
                    "amount": 500000,
                    "message": null,
                    "gateway_response": "Successful",
                    "paid_at": "2024-11-15T11:42:29.000Z",
                    "created_at": "2024-11-15T11:41:52.000Z",
                    "channel": "card",
                    "currency": "NGN",
                    "ip_address": "102.89.42.149",
                    "metadata": "",
                    "fees_breakdown": null,
                    "log": null,
                    "fees": 17500,
                    "fees_split": null,
                    "authorization": {
                        "authorization_code": "AUTH_gdutbv7fxk",
                        "bin": "408408",
                        "last4": "4081",
                        "exp_month": "12",
                        "exp_year": "2030",
                        "channel": "card",
                        "card_type": "visa ",
                        "bank": "TEST BANK",
                        "country_code": "NG",
                        "brand": "visa",
                        "reusable": true,
                        "signature": "SIG_615yelQVtyvVGkKXl6ty",
                        "account_name": null
                    },
                    "customer": {
                        "id": 206121805,
                        "first_name": null,
                        "last_name": null,
                        "email": "olumayokunolayinka@gmail.com",
                        "customer_code": "CUS_a786i3471mgk8h7",
                        "phone": null,
                        "metadata": null,
                        "risk_action": "default",
                        "international_format_phone": null
                    },
                    "plan": {},
                    "subaccount": {},
                    "split": {},
                    "order_id": null,
                    "paidAt": "2024-11-15T11:42:29.000Z",
                    "requested_amount": 500000,
                    "pos_transaction_data": null,
                    "source": {
                        "type": "api",
                        "source": "merchant_api",
                        "entry_point": "transaction_initialize",
                        "identifier": null
                    }
                }
            }';

        (new WebhookController())->handleChargeSuccess(json_decode($payload, true));

        Queue::assertPushed(HandleWebhookJob::class);

        app()->call([new HandleWebhookJob('success_event', json_decode($payload, true), $transaction), 'handle']);

        $this->assertDatabaseCount('gateway_transactions', 1);

        expect(GatewayTransaction::first()->status)->toBe(TransactionEnum::STATUS['success']);

        expect(GatewayTransaction::first()->customer_id)->toBe($customer->id);

        expect(GatewayTransaction::first()->type)->toBe(TransactionEnum::TYPE['card_tokenization']);

        $cardToken = CardTokenization::first();

        expect($cardToken->customer_id)->toBe($customer->id);

        Queue::assertPushed(SendWebhookJob::class);

       // dd(WebhookLog::first());
    });

    it('Successful payment link', function () {
        Queue::fake();

       (new GenerateAccessGatewayAction)->execute();

       $accessGateway = AccessGateway::first();

       (new UpdateWebhookUrlAction)->execute($accessGateway, 'https://www.google.com');

       $customer = Customer::factory()->create();

       $transaction = $accessGateway->tempTransactions()->create([
           'customer_id' => $customer->id,
           'type' => TransactionEnum::TYPE['payment_link'],
           'provider' => TransactionEnum::PROVIDERS['paystack'],
           'status' => TransactionEnum::STATUS['pending'],
           'reference' => 'ref',
           'amount' => 5000,
       ]);

           $payload = '{
                   "event": "charge.success",
                   "data": {
                       "id": 4378045029,
                       "domain": "test",
                       "status": "success",
                       "reference": "ref",
                       "amount": 500000,
                       "message": null,
                       "gateway_response": "Successful",
                       "paid_at": "2024-11-15T11:42:29.000Z",
                       "created_at": "2024-11-15T11:41:52.000Z",
                       "channel": "card",
                       "currency": "NGN",
                       "ip_address": "102.89.42.149",
                       "metadata": "",
                       "fees_breakdown": null,
                       "log": null,
                       "fees": 17500,
                       "fees_split": null,
                       "authorization": {
                           "authorization_code": "AUTH_gdutbv7fxk",
                           "bin": "408408",
                           "last4": "4081",
                           "exp_month": "12",
                           "exp_year": "2030",
                           "channel": "card",
                           "card_type": "visa ",
                           "bank": "TEST BANK",
                           "country_code": "NG",
                           "brand": "visa",
                           "reusable": true,
                           "signature": "SIG_615yelQVtyvVGkKXl6ty",
                           "account_name": null
                       },
                       "customer": {
                           "id": 206121805,
                           "first_name": null,
                           "last_name": null,
                           "email": "olumayokunolayinka@gmail.com",
                           "customer_code": "CUS_a786i3471mgk8h7",
                           "phone": null,
                           "metadata": null,
                           "risk_action": "default",
                           "international_format_phone": null
                       },
                       "plan": {},
                       "subaccount": {},
                       "split": {},
                       "order_id": null,
                       "paidAt": "2024-11-15T11:42:29.000Z",
                       "requested_amount": 500000,
                       "pos_transaction_data": null,
                       "source": {
                           "type": "api",
                           "source": "merchant_api",
                           "entry_point": "transaction_initialize",
                           "identifier": null
                       }
                   }
               }';

           (new WebhookController())->handleChargeSuccess(json_decode($payload, true));

           Queue::assertPushed(HandleWebhookJob::class);

           app()->call([new HandleWebhookJob('success_event', json_decode($payload, true), $transaction), 'handle']);

           $this->assertDatabaseCount('gateway_transactions', 1);

           expect(GatewayTransaction::first()->status)->toBe(TransactionEnum::STATUS['success']);

           expect(GatewayTransaction::first()->customer_id)->toBe($customer->id);

           expect(GatewayTransaction::first()->type)->toBe(TransactionEnum::TYPE['payment_link']);

           Queue::assertPushed(SendWebhookJob::class);

            //dd(WebhookLog::first());
    });

    it('Successful transfer', function () {
        Queue::fake();

       (new GenerateAccessGatewayAction)->execute();

       $accessGateway = AccessGateway::first();

       (new UpdateWebhookUrlAction)->execute($accessGateway, 'https://www.google.com');

       $customer = Customer::factory()->create();

       $transaction = $accessGateway->tempTransactions()->create([
           'customer_id' => $customer->id,
           'type' => TransactionEnum::TYPE['transfer'],
           'provider' => TransactionEnum::PROVIDERS['paystack'],
           'status' => TransactionEnum::STATUS['pending'],
           'reference' => 'ref',
           'amount' => 5000,
       ]);

           $payload = '{
  "event": "transfer.success",
  "data": {
    "amount": 30000,
    "currency": "NGN",
    "domain": "test",
    "failures": null,
    "id": 37272792,
    "integration": {
      "id": 463433,
      "is_live": true,
      "business_name": "Boom Boom Industries NG"
    },
    "reason": "Have fun...",
    "reference": "ref",
    "source": "balance",
    "source_details": null,
    "status": "success",
    "titan_code": null,
    "transfer_code": "TRF_wpl1dem4967avzm",
    "transferred_at": null,
    "recipient": {
      "active": true,
      "currency": "NGN",
      "description": "",
      "domain": "test",
      "email": null,
      "id": 8690817,
      "integration": 463433,
      "metadata": null,
      "name": "Jack Sparrow",
      "recipient_code": "RCP_a8wkxiychzdzfgs",
      "type": "nuban",
      "is_deleted": false,
      "details": {
        "account_number": "0000000000",
        "account_name": null,
        "bank_code": "011",
        "bank_name": "First Bank of Nigeria"
      },
      "created_at": "2020-09-03T12:11:25.000Z",
      "updated_at": "2020-09-03T12:11:25.000Z"
    },
    "session": {
      "provider": null,
      "id": null
    },
    "created_at": "2020-10-26T12:28:57.000Z",
    "updated_at": "2020-10-26T12:28:57.000Z"
  }
}
';

           (new WebhookController())->handleTransferSuccess(json_decode($payload, true));

           Queue::assertPushed(HandleWebhookJob::class);

           app()->call([new HandleWebhookJob('success_event', json_decode($payload, true), $transaction), 'handle']);

           $this->assertDatabaseCount('gateway_transactions', 1);

           expect(GatewayTransaction::first()->status)->toBe(TransactionEnum::STATUS['success']);

           expect(GatewayTransaction::first()->customer_id)->toBe($customer->id);

           expect(GatewayTransaction::first()->type)->toBe(TransactionEnum::TYPE['transfer']);

           Queue::assertPushed(SendWebhookJob::class);

           //dd(WebhookLog::first());

    });

    it('Process failed transfer', function () {
        Queue::fake();

       (new GenerateAccessGatewayAction)->execute();

       $accessGateway = AccessGateway::first();

       (new UpdateWebhookUrlAction)->execute($accessGateway, 'https://www.google.com');

       $customer = Customer::factory()->create();

       $transaction = $accessGateway->tempTransactions()->create([
           'customer_id' => $customer->id,
           'type' => TransactionEnum::TYPE['transfer'],
           'provider' => TransactionEnum::PROVIDERS['paystack'],
           'status' => TransactionEnum::STATUS['pending'],
           'reference' => 'ref',
           'amount' => 5000,
       ]);

           $payload = '{
  "event": "transfer.failed",
  "data": {
    "amount": 200000,
    "currency": "NGN",
    "domain": "test",
    "failures": null,
    "id": 69123462,
    "integration": {
      "id": 100043,
      "is_live": true,
      "business_name": "Paystack"
    },
    "reason": "Enjoy",
    "reference": "ref",
    "source": "balance",
    "source_details": null,
    "status": "failed",
    "titan_code": null,
    "transfer_code": "TRF_chs98y5rykjb47w",
    "transferred_at": null,
    "recipient": {
      "active": true,
      "currency": "NGN",
      "description": null,
      "domain": "test",
      "email": "test@email.com",
      "id": 13584206,
      "integration": 100043,
      "metadata": null,
      "name": "Ted Lasso",
      "recipient_code": "RCP_cjcua8itre45gs",
      "type": "nuban",
      "is_deleted": false,
      "details": {
        "authorization_code": null,
        "account_number": "0123456789",
        "account_name": "Ted Lasso",
        "bank_code": "011",
        "bank_name": "First Bank of Nigeria"
      },
      "created_at": "2021-04-12T15:30:14.000Z",
      "updated_at": "2021-04-12T15:30:14.000Z"
    },
    "session": {
      "provider": "nip",
      "id": "74849400998877667"
    },
    "created_at": "2021-04-12T15:30:15.000Z",
    "updated_at": "2021-04-12T15:41:21.000Z"
  }
}';

           (new WebhookController())->handleTransferFailed(json_decode($payload, true));

           Queue::assertPushed(HandleWebhookJob::class, fn($job) => $job->event == 'failed_event');

           app()->call([new HandleWebhookJob('failed_event', json_decode($payload, true), $transaction), 'handle']);

           $this->assertDatabaseCount('gateway_transactions', 1);

           expect(GatewayTransaction::first()->status)->toBe(TransactionEnum::STATUS['failed']);

           expect(GatewayTransaction::first()->customer_id)->toBe($customer->id);

           expect(GatewayTransaction::first()->type)->toBe(TransactionEnum::TYPE['transfer']);

           Queue::assertPushed(SendWebhookJob::class);

           //dd(WebhookLog::first());

    });

    it('Process reversed transfer', function () {
        Queue::fake();

       (new GenerateAccessGatewayAction)->execute();

       $accessGateway = AccessGateway::first();

       (new UpdateWebhookUrlAction)->execute($accessGateway, 'https://www.google.com');

       $customer = Customer::factory()->create();

       $transaction = $accessGateway->tempTransactions()->create([
           'customer_id' => $customer->id,
           'type' => TransactionEnum::TYPE['transfer'],
           'provider' => TransactionEnum::PROVIDERS['paystack'],
           'status' => TransactionEnum::STATUS['pending'],
           'reference' => 'ref',
           'amount' => 5000,
       ]);

           $payload = '{
    "event": "transfer.reversed",
    "data": {
        "amount": 10000,
        "currency": "NGN",
        "domain": "live",
        "failures": null,
        "id": 20615868,
        "integration": {
        "id": 100073,
        "is_live": true,
        "business_name": "Nights Watch Inc"
        },
        "reason": "test balance ledger elastic changes",
    "reference": "ref",
    "source": "balance",
    "source_details": null,
    "status": "reversed",
    "titan_code": null,
    "transfer_code": "TRF_js075pj9u07f34l",
    "transferred_at": "2020-03-24T07:14:00.000Z",
    "recipient": {
      "active": true,
      "currency": "NGN",
      "description": null,
      "domain": "live",
      "email": "jon@sn.ow",
      "id": 1476759,
      "integration": 100073,
      "metadata": null,
      "name": "JON SNOW",
      "recipient_code": "RCP_hmcj8ciho490bvi",
      "type": "nuban",
      "is_deleted": false,
      "details": {
        "authorization_code": null,
        "account_number": "0000000000",
        "account_name": null,
        "bank_code": "011",
        "bank_name": "First Bank of Nigeria"
      },
      "created_at": "2019-04-10T08:39:10.000Z",
      "updated_at": "2019-11-27T20:43:57.000Z"
    },
    "session": {
      "provider": "nip",
      "id": "110006200324071331002061586801"
    },
    "created_at": "2020-03-24T07:13:31.000Z",
    "updated_at": "2020-03-24T07:14:55.000Z"
  }
}';

           (new WebhookController())->handleTransferReversed(json_decode($payload, true));

           Queue::assertPushed(HandleWebhookJob::class, fn($job) => $job->event == 'failed_event');

           $event = TransactionEnum::TYPE['transfer'].'.reversed';

           app()->call([new HandleWebhookJob('failed_event', json_decode($payload, true), $transaction, $event,
           TransactionEnum::STATUS['reversed'],), 'handle']);

           $this->assertDatabaseCount('gateway_transactions', 1);

           expect(GatewayTransaction::first()->status)->toBe(TransactionEnum::STATUS['reversed']);

           expect(GatewayTransaction::first()->customer_id)->toBe($customer->id);

           expect(GatewayTransaction::first()->type)->toBe(TransactionEnum::TYPE['transfer']);

           Queue::assertPushed(SendWebhookJob::class);

           //dd(WebhookLog::first());

    });

    it('Successful direct debit', function () {
        Queue::fake();

       (new GenerateAccessGatewayAction)->execute();

       $accessGateway = AccessGateway::first();

       (new UpdateWebhookUrlAction)->execute($accessGateway, 'https://www.google.com');

       $customer = Customer::factory()->create();

       $transaction = $accessGateway->tempTransactions()->create([
           'customer_id' => $customer->id,
           'type' => TransactionEnum::TYPE['direct_debit'],
           'provider' => TransactionEnum::PROVIDERS['paystack'],
           'status' => TransactionEnum::STATUS['pending'],
           'reference' => 'ref',
           'amount' => 5000,
       ]);

           $payload = '{
  "event": "direct_debit.authorization.active",
  "data": {
    "authorization_code": "AUTH_JV4T9Wawdj",
    "active": true,
    "last4": "1234",
    "channel": "direct_debit",
    "card_type": "mandate",
    "bank": "Guaranty Trust Bank",
    "exp_month": 1,
    "exp_year": 2034,
    "country_code": "NG",
    "brand": "Guaranty Trust Bank",
    "reusable": true,
    "signature": "SIG_u8SqR3E6ty2koQ9i5IrI",
    "account_name": "Ravi Demo",
    "integration": 191390,
    "domain": "live",
    "reference": "ref",
    "customer": {
      "first_name": "Ravi",
      "last_name": "Demo",
      "code": "CUS_g0a2pm2ilthhh62",
      "email": "ravi@demo.com",
      "phone": "",
      "metadata": null,
      "risk_action": "default"
    }
  }
}';

           (new WebhookController())->handleChargeSuccess(json_decode($payload, true));

           Queue::assertPushed(HandleWebhookJob::class);

           app()->call([new HandleWebhookJob('success_event', json_decode($payload, true), $transaction), 'handle']);

           $this->assertDatabaseCount('gateway_transactions', 1);

           expect(GatewayTransaction::first()->status)->toBe(TransactionEnum::STATUS['success']);

           expect(GatewayTransaction::first()->customer_id)->toBe($customer->id);

           expect(GatewayTransaction::first()->type)->toBe(TransactionEnum::TYPE['direct_debit']);

           Queue::assertPushed(SendWebhookJob::class);

           //dd(WebhookLog::first());
       });

    it('Virtual account failed customer identification', function () {
        Queue::fake();

       (new GenerateAccessGatewayAction)->execute();

       $accessGateway = AccessGateway::first();

       (new UpdateWebhookUrlAction)->execute($accessGateway, 'https://www.google.com');

       $customer = Customer::factory()->create();

       VirtualAccountProcess::create([
        'customer_id' => $customer->id,
        'access_gateway_id' => $accessGateway->id,
    ]);

    $payload = '{
    "event": "customeridentification.failed",
    "data": {
        "customer_id": 82796315,
        "customer_code": "CUS_XXXXXXXXXXXXXXX",
        "email": "customer@gmail.com",
        "identification": {
        "country": "NG",
        "type": "bank_account",
        "bvn": "123*****456",
        "account_number": "012****345",
        "bank_code": "999991"
        },
        "reason": "Account number or BVN is incorrect"
    }
    }';

    (new WebhookController())->handleCustomerIdentificationFailed(json_decode($payload, true));

    Queue::assertPushed(HandleWebhookJob::class);

    app()->call([new HandleWebhookJob('customer_identity_failed', json_decode($payload, true), $customer), 'handle']);

    expect(CustomerVirtualAccount::count())->toBe(0);

    Queue::assertPushed(SendWebhookJob::class);

    //dd(WebhookLog::first());
    });

    it('Virtual account failed', function () {
        Queue::fake();

       (new GenerateAccessGatewayAction)->execute();

       $accessGateway = AccessGateway::first();

       (new UpdateWebhookUrlAction)->execute($accessGateway, 'https://www.google.com');

       $customer = Customer::factory()->create();

       VirtualAccountProcess::create([
        'customer_id' => $customer->id,
        'access_gateway_id' => $accessGateway->id,
    ]);

    $payload = '{
        "event": "dedicatedaccount.assign.failed",
        "data": {
            "customer": {
            "id": 100110,
            "first_name": "John",
            "last_name": "Doe",
            "email": "customer@gmail.com",
            "customer_code": "CUS_hcekca0j0bbg2m4",
            "phone": "+2348100000000",
            "metadata": {},
            "risk_action": "default",
            "international_format_phone": "+2348100000000"
            },
            "dedicated_account": null,
            "identification": {
            "status": "failed"
            }
        }
        }
        ';

    (new WebhookController())->handleDedicatedAccountAssignFailed(json_decode($payload, true));

    Queue::assertPushed(HandleWebhookJob::class);

    app()->call([new HandleWebhookJob('virtual_account_creation_failed', json_decode($payload, true), $customer), 'handle']);

    expect(CustomerVirtualAccount::count())->toBe(0);

    Queue::assertPushed(SendWebhookJob::class);

    //dd(WebhookLog::first());
    });

    it('Virtual account success', function () {
        Queue::fake();

       (new GenerateAccessGatewayAction)->execute();

       $accessGateway = AccessGateway::first();

       (new UpdateWebhookUrlAction)->execute($accessGateway, 'https://www.google.com');

       $customer = Customer::factory()->create();

       VirtualAccountProcess::create([
        'customer_id' => $customer->id,
        'access_gateway_id' => $accessGateway->id,
    ]);

    $payload = '{
  "event": "dedicatedaccount.assign.success",
  "data": {
    "customer": {
      "id": 100110,
      "first_name": "John",
      "last_name": "Doe",
      "email": "customer@gmail.com",
      "customer_code": "CUS_hp05n9khsqcesz2",
      "phone": "+2348100000000",
      "metadata": {},
      "risk_action": "default",
      "international_format_phone": "+2348100000000"
    },
    "dedicated_account": {
      "bank": {
        "name": "Test Bank",
        "id": 20,
        "slug": "test-bank"
      },
      "account_name": "PAYSTACK/John Doe",
      "account_number": "1234567890",
      "assigned": true,
      "currency": "NGN",
      "metadata": null,
      "active": true,
      "id": 987654,
      "created_at": "2022-06-21T17:12:40.000Z",
      "updated_at": "2022-08-12T14:02:51.000Z",
      "assignment": {
        "integration": 100123,
        "assignee_id": 100110,
        "assignee_type": "Customer",
        "expired": false,
        "account_type": "PAY-WITH-TRANSFER-RECURRING",
        "assigned_at": "2022-08-12T14:02:51.614Z",
        "expired_at": null
      }
    },
    "identification": {
      "status": "success"
    }
  }
}';

    (new WebhookController())->handleDedicatedAccountAssignSuccess(json_decode($payload, true));

    Queue::assertPushed(HandleWebhookJob::class);

    app()->call([new HandleWebhookJob('virtual_account_creation_success', json_decode($payload, true), $customer), 'handle']);

    expect(CustomerVirtualAccount::count())->toBe(1);

    Queue::assertPushed(SendWebhookJob::class);
    });

    it('Virtual account credit success', function () {
        Queue::fake();

       (new GenerateAccessGatewayAction)->execute();

       $accessGateway = AccessGateway::first();

       (new UpdateWebhookUrlAction)->execute($accessGateway, 'https://www.google.com');

       $customer = Customer::factory()->create();

       $customer->virtualAccounts()->create([
        'access_gateway_id' => 1,
        'provider' => TransactionEnum::PROVIDERS['paystack'],
        'account_number' => '1234567890',
        'account_name' => 'John Doe',
        'bank_name' => 'Test Bank',
        'status' => 'active',
        'meta' => [],
    ]);

    $payload = '{
    "event": "charge.success",
    "data": {
       "id": 393930304322,
   "domain": "live",
   "status": "success",
   "reference": "020293904002",
   "amount": 10000,
   "message": null,
   "gateway_response": "Approved",
   "paid_at": "2024-12-02T12:05:12.000Z",
   "created_at": "2024-12-02T12:05:12.000Z",
   "channel": "dedicated_nuban",
   "currency": "NGN",
   "ip_address": null,
   "metadata": {
       "receiver_account_number": "1234567890",
       "receiver_bank": "Test Bank",
       "receiver_account_type": null,
       "custom_fields": [
           {
               "display_name": "Receiver Account",
               "variable_name": "receiver_account_number",
               "value": "1234567890"
           },
           {
               "display_name": "Receiver Bank",
               "variable_name": "receiver_bank",
               "value": "Wema Bank"
           }
       ]
   },
   "fees_breakdown": {
       "amount": "100",
       "formula": null,
       "type": "paystack"
   },
   "log": null,
   "fees": 100,
   "fees_split": null,
   "authorization": {
       "authorization_code": "Auth039292",
       "bin": "219XXX",
       "last4": "X124",
       "exp_month": "11",
       "exp_year": "2024",
       "channel": "dedicated_nuban",
       "card_type": "transfer",
       "bank": "Test Bank",
       "country_code": "NG",
       "brand": "Managed Account",
       "reusable": false,
       "signature": null,
       "account_name": null,
       "sender_country": "NG",
       "sender_bank": "Test Bank",
       "sender_bank_account_number": "XXXXXX1234",
       "sender_name": "John",
       "narration": " Testing",
       "receiver_bank_account_number": "1234567890",
       "receiver_bank": "Test Bank"
   }
    }
    }';

    (new WebhookController())->handleChargeSuccess(json_decode($payload, true));

    Queue::assertPushed(HandleDVACreditWebhook::class);

    app()->call([new HandleDVACreditWebhook(json_decode($payload, true)), 'handle']);

    expect(CustomerVirtualAccount::count())->toBe(1);

    Queue::assertPushed(SendWebhookJob::class);

    expect(GatewayTransaction::count())->toBe(1);

    expect(GatewayTransaction::first()->provider_reference)->toBe('020293904002');
    });
});

