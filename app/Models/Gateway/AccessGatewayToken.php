<?php

namespace App\Models\Gateway;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class AccessGatewayToken extends Model
{
    /** @use HasFactory<\Database\Factories\Gateway\AccessGatewayTokenFactory> */
    use HasFactory;

    protected $fillable = [
        'access_gateway_id',
        'token',
        'test_token',
        'access_id',
        'active',
    ];

    public static function retrieveToken(string $secretKey): ?self
    {
        $keys = self::pluck('token');

        foreach($keys as $key) {
            //Crypt::
            if (Crypt::decrypt( $key) === $secretKey) {
                return self::where('token', $key)->first();
            }
        }

        return null;
    }

    public static function validToken(string $secretKey): bool
    {
        return self::retrieveToken($secretKey)?->active ? true : false;
    }

    public function accessGateway(): BelongsTo
    {
        return $this->belongsTo(AccessGateway::class);
    }
}
