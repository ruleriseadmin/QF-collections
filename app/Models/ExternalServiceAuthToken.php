<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class ExternalServiceAuthToken extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    const NIBSS = 'nibss';

    public static function setAuthToken(string $service, string $token, int $expiresIn)
    {
        return self::updateOrCreate([
            'service' => $service,
        ],[
            'token' => Crypt::encrypt($token),
            'expires_at' => Carbon::now()->addSeconds($expiresIn - 5) // 5 seconds buffer for safety
        ]);
    }

    public static function getAuthToken(string $service): mixed
    {
        $service = self::where('service', $service)->first();

        if ( ! $service ) return null;

        return Carbon::now()->lt($service->expires_at) ? Crypt::decrypt($service->token) : null;
    }
}
