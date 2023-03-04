<?php

namespace Invoate\WebAuthn\Actions;

use Exception;
use Invoate\WebAuthn\Contracts\WebAuthnticatable;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialLoader;
use Webauthn\PublicKeyCredentialSource;

class ValidateNewCredential
{
    protected $publicKeyCredentialLoader;

    protected $authenticatorAttestationResponseValidator;

    public function __construct(PublicKeyCredentialLoader $publicKeyCredentialLoader, AuthenticatorAttestationResponseValidator $authenticatorAttestationResponseValidator)
    {
        $this->publicKeyCredentialLoader = $publicKeyCredentialLoader;
        $this->authenticatorAttestationResponseValidator = $authenticatorAttestationResponseValidator;
    }

    public function __invoke($data): PublicKeyCredentialSource
    {
        $publicKeyCredential = $this->publicKeyCredentialLoader->loadArray($data);

        $authenticatorAttestationResponse = $publicKeyCredential->getResponse();
        if (! $authenticatorAttestationResponse instanceof AuthenticatorAttestationResponse) {
            throw new Exception("Invalid response");
        }

        return $this->authenticatorAttestationResponseValidator->check(
            authenticatorAttestationResponse: $authenticatorAttestationResponse,
            publicKeyCredentialCreationOptions: $this->publicKeyCredentialCreationOptions(),
            request: 'coordina.test'
        );
    }

    protected function publicKeyCredentialCreationOptions(): PublicKeyCredentialCreationOptions
    {
        $data = session()->get(config('webauthn.registration.session-key', 'webauthn'));

        return PublicKeyCredentialCreationOptions::createFromArray($data);
    }
}
