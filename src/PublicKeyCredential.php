<?php

namespace Invoate\WebAuthn;

use Illuminate\Contracts\Auth\Guard;
use Invoate\WebAuthn\Contracts\WebAuthnticatable;
use Invoate\WebAuthn\Models\Credential;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialSourceRepository;
use Webauthn\PublicKeyCredentialUserEntity;

class PublicKeyCredential implements PublicKeyCredentialSourceRepository
{
    protected $guard;

    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    public function findOneByCredentialId(string $publicKeyCredentialId): ?PublicKeyCredentialSource
    {
        return Credential::where('credential_id', $publicKeyCredentialId)->first();
    }

    public function findAllForUserEntity(PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity): array
    {
        return $this->webAuthnticatable()->credentials;
    }

    public function saveCredentialSource(PublicKeyCredentialSource $publicKeyCredentialSource): void
    {
        //
    }

    protected function webAuthnticatable(): WebAuthnticatable
    {
        $user = $this->guard->user();

        if ($user instanceof WebAuthnticatable) {
            return $user;
        }

        throw('Invalid user type');
    }
}
