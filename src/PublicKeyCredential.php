<?php

namespace Invoate\WebAuthn;

use Illuminate\Contracts\Auth\Guard;
use Invoate\WebAuthn\Contracts\WebAuthnticatable;
use Invoate\WebAuthn\Models\Credential;
use ParagonIE\ConstantTime\Base64UrlSafe;
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
        $credential = Credential::query()
            ->where(function ($query) use ($publicKeyCredentialId) {
                $query->where('credential_id', Base64UrlSafe::encode($publicKeyCredentialId))
                    ->orWhere('credential_id', Base64UrlSafe::encodeUnpadded($publicKeyCredentialId));
            })
            ->first();

        return optional($credential)->getPublicKeyCredentialSource();
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
