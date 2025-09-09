<?php

namespace App\Models\Recipients;

use App\Models\Gateway\AccessGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'identifier',
        'name',
        'account_number',
        'bank_code',
        'active',
        'meta',
        'access_gateway_id',
    ];

    protected $casts = [
        'meta' => 'array',
        'active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) str()->uuid();
            $model->active = true;
        });
    }

    public static function whereAccountNumber(string $accountNumber): ?self
    {
        return self::where('account_number', $accountNumber)->first();
    }

    public function accessGateway(): BelongsTo
    {
        return $this->belongsTo(AccessGateway::class);
    }
}
