<?php

namespace Invoate\WebAuthn\Http\Controllers;

use Illuminate\Routing\Controller;
use Invoate\WebAuthn\Contracts\WebAuthnticatable;
use Invoate\WebAuthn\Http\Requests\AuthenticationOptionsRequest;
use Invoate\WebAuthn\Http\Requests\AuthenticationRequest;
use Invoate\WebAuthn\Models\Credential;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialRequestOptions;

class AuthenticationController extends Controller
{
    public function generateOptions(AuthenticationOptionsRequest $request)
    {
        $publicKeyCredentialRequestOptions = $this->publicKeyCredentialRequestOptions($request->user());

        session()->put(
            config('webauthn.authentication.session-key', 'webauthn'),
            $publicKeyCredentialRequestOptions->jsonSerialize()
        );

        return $publicKeyCredentialRequestOptions;
    }

    public function verifyAuthentication(AuthenticationRequest $request)
    {
    }

    protected function publicKeyCredentialRequestOptions(WebAuthnticatable $user): PublicKeyCredentialRequestOptions
    {
        $allowedCredentials = $user->credentials->map(function (Credential $credential) {
            return PublicKeyCredentialDescriptor::create(
                type: PublicKeyCredentialDescriptor::CREDENTIAL_TYPE_PUBLIC_KEY,
                id: $credential->credential_id,
                transports: $credential->transports
            );
        });

        return PublicKeyCredentialRequestOptions::create(random_bytes(config('webauthn.challenge.bytes')))
            ->allowCredentials(...$allowedCredentials)
            ->setTimeout(config('webauthn.challenge.timeout'))
            ->setRpId('coordina.test')
            ->setUserVerification(PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_PREFERRED);
    }
}
