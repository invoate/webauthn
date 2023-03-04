<?php

namespace Invoate\WebAuthn\Models;

use Illuminate\Database\Eloquent\Model;
use Invoate\WebAuthn\Casts\Base64;

class Credential extends Model
{
    protected $casts = [
        'transports' => 'array',
        'extension' => 'array',
        'certificates' => 'array',
        'discoverable' => 'array',
        'last_used_at' => 'datetime',
        'credential_id' => Base64::class,
        'public_key' => Base64::class,
    ];
}
