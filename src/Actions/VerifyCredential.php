<?php

namespace Invoate\WebAuthn\Actions;

use Exception;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\PublicKeyCredentialLoader;
use Webauthn\PublicKeyCredentialRequestOptions;

class VerifyCredential
{
    protected $publicKeyCredentialLoader;

    protected $authenticatorAssertionResponseValidator;

    public function __construct(PublicKeyCredentialLoader $publicKeyCredentialLoader, AuthenticatorAssertionResponseValidator $authenticatorAssertionResponseValidator)
    {
        $this->publicKeyCredentialLoader = $publicKeyCredentialLoader;
        $this->authenticatorAssertionResponseValidator = $authenticatorAssertionResponseValidator;
    }

    public function __invoke($data): array
    {
        $publicKeyCredential = $this->publicKeyCredentialLoader->loadArray($data);

        $authenticatorAssertionResponse = $publicKeyCredential->getResponse();
        if (!$authenticatorAssertionResponse instanceof AuthenticatorAssertionResponse) {
            throw new Exception('Invalid response');
        }

        $publicKeyCredentialRequestOptions = $this->publicKeyCredentialRequestOptions();

        return [$publicKeyCredentialRequestOptions, $this->authenticatorAssertionResponseValidator->check(
            credentialId: $publicKeyCredential->getRawId(),
            authenticatorAssertionResponse: $authenticatorAssertionResponse,
            publicKeyCredentialRequestOptions: $publicKeyCredentialRequestOptions,
            request: 'coordina.test',
            userHandle: null
        )];
    }

    protected function publicKeyCredentialRequestOptions(): PublicKeyCredentialRequestOptions
    {
        $data = session()->pull(config('webauthn.registration.session-key', 'webauthn'));

        return PublicKeyCredentialRequestOptions::createFromArray($data);
    }
}
