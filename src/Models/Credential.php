<?php

namespace Invoate\WebAuthn\Models;

use Illuminate\Database\Eloquent\Model;
use Invoate\WebAuthn\Casts\Base64;
use Symfony\Component\Uid\Uuid;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\TrustPath\EmptyTrustPath;

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

    public function getPublicKeyCredentialSource(): PublicKeyCredentialSource
    {
        return new PublicKeyCredentialSource(
            $this->credential_id,
            'public-key',
            $this->transports,
            $this->attestation_type,
            new EmptyTrustPath,
            Uuid::fromString($this->aaguid),
            $this->public_key,
            $this->webauthnticatable_id,
            $this->counter ?? 0
        );
    }
}
