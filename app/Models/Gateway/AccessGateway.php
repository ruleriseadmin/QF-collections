<?php

namespace App\Models\Gateway;

use App\Models\CardTokenization;
use App\Models\Customer\Customer;
use App\Models\DirectDebit\RevokedMandate;
use App\Models\Recipients\Recipient;
use App\Models\Webhook\WebhookConfig;
use App\Models\Webhook\WebhookLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessGateway extends Model
{
    /** @use HasFactory<\Database\Factories\Gateway\AccessGatewayFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'uuid',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) str()->uuid();
            $model->name = strtolower($model->name);
        });
    }

    public function accessToken(): HasOne
    {
        return $this->hasOne(AccessGatewayToken::class);
    }

    public function webhookConfig(): HasOne
    {
        return $this->hasOne(WebhookConfig::class);
    }

    public function webhookLogs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }

    public function tempTransactions(): HasMany
    {
        return $this->hasMany(GatewayTempTransaction::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(GatewayTransaction::class);
    }

    public function cardTokens(): HasMany
    {
        return $this->hasMany(CardTokenization::class);
    }

    public function recepients(): HasMany
    {
        return $this->hasMany(Recipient::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function revokedMandates(): HasMany
    {
        return $this->hasMany(RevokedMandate::class);
    }
}
