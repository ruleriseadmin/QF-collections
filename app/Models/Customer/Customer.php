<?php

namespace App\Models\Customer;

use App\Models\CardTokenization;
use App\Models\Gateway\AccessGateway;
use Illuminate\Database\Eloquent\Model;
use App\Models\Gateway\GatewayTransaction;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use SoftDeletes;

     /** @use HasFactory<\Database\Factories\Customer\CustomerFactory> */
     use HasFactory;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'phone_numbers',
        'mono_id',
        'address',
    ];

    protected $casts = [
        'phone_numbers' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) str()->uuid();
            $model->first_name = strtolower($model->first_name);
            $model->last_name = strtolower($model->last_name);
            $model->email = strtolower($model->email);
        });
    }

    public static function whereEmail(string $email): ? self
    {
        return self::where('email', $email)->first();
    }

    public function virtualAccounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CustomerVirtualAccount::class);
    }

    public function virtualAccountsPerAccessGateway(AccessGateway  $gateway): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->virtualAccounts()->where('access_gateway_id', $gateway->id);
    }

    public function cardTokens(): HasMany
    {
        return $this->hasMany(CardTokenization::class);
    }


    public function transactions(): HasMany
    {
        return $this->hasMany(GatewayTransaction::class, 'customer_id', 'id');
    }

    public function directDebitTransactions(): HasMany
    {
        return $this->transactions()->where('type', 'direct_debit');
    }

    public function accessGateway(): BelongsTo
    {
        return $this->belongsTo(AccessGateway::class);
    }
}
