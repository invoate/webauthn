<?php

namespace Invoate\WebAuthn\Actions;

use Exception;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialLoader;

class ValidateNewCredential
{
    protected $publicKeyCredentialLoader;

    protected $authenticatorAttestationResponseValidator;

    public function __construct(PublicKeyCredentialLoader $publicKeyCredentialLoader, AuthenticatorAttestationResponseValidator $authenticatorAttestationResponseValidator)
    {
        $this->publicKeyCredentialLoader = $publicKeyCredentialLoader;
        $this->authenticatorAttestationResponseValidator = $authenticatorAttestationResponseValidator;
    }

    public function __invoke($data): array
    {
        $publicKeyCredential = $this->publicKeyCredentialLoader->loadArray($data);

        $authenticatorAttestationResponse = $publicKeyCredential->getResponse();
        if (! $authenticatorAttestationResponse instanceof AuthenticatorAttestationResponse) {
            throw new Exception('Invalid response');
        }

        $publicKeyCredentialCreationOptions = $this->publicKeyCredentialCreationOptions();

        return [$publicKeyCredentialCreationOptions, $this->authenticatorAttestationResponseValidator->check(
            authenticatorAttestationResponse: $authenticatorAttestationResponse,
            publicKeyCredentialCreationOptions: $publicKeyCredentialCreationOptions,
            request: 'coordina.test'
        )];
    }

    protected function publicKeyCredentialCreationOptions(): PublicKeyCredentialCreationOptions
    {
        $data = session()->pull(config('webauthn.registration.session-key', 'webauthn'));

        return PublicKeyCredentialCreationOptions::createFromArray($data);
    }
}
