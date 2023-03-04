<?php

namespace Invoate\WebAuthn\Http\Controllers;

use Cose\Algorithms;
use Illuminate\Routing\Controller;
use Invoate\WebAuthn\Actions\CreateNewCredential;
use Invoate\WebAuthn\Actions\ValidateNewCredential;
use Invoate\WebAuthn\Contracts\WebAuthnticatable;
use Invoate\WebAuthn\Http\Requests\RegistrationOptionsRequest;
use Invoate\WebAuthn\Http\Requests\RegistrationRequest;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;

class RegistrationController extends Controller
{
    public function generateOptions(RegistrationOptionsRequest $request)
    {
        $publicKeyCredentialCreationOptions = $this->publicKeyCredentialCreationOptions($request->user());

        session()->put(
            config('webauthn.registration.session-key', 'webauthn'),
            $publicKeyCredentialCreationOptions->jsonSerialize()
        );

        return $publicKeyCredentialCreationOptions;
    }

    public function verifyRegistration(RegistrationRequest $request, ValidateNewCredential $validator, CreateNewCredential $creator)
    {
        $data = $request->all();

        [$publicKeyCredentialCreationOptions, $publicKeyCredentialSource] = $validator($data);

        $creator($request->user(), $data, $publicKeyCredentialSource, $publicKeyCredentialCreationOptions);

        return response()->json(['verified' => true]);
    }

    protected function publicKeyCredentialCreationOptions(WebAuthnticatable $user): PublicKeyCredentialCreationOptions
    {
        $rp = PublicKeyCredentialRpEntity::create(
            name: 'Testing',
            id: 'coordina.test'
        );

        $user = PublicKeyCredentialUserEntity::create(
            name: $user->name,
            id: $user->id,
            displayName: $user->name
        );

        $challenge = random_bytes(config('webauthn.challenge.bytes', 64));

        $publicKeyCredentialParametersList = [
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_ES256),
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_ES256K),
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_ES384),
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_ES512),
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_RS256),
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_RS384),
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_RS512),
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_PS256),
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_PS384),
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_PS512),
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_ED256),
            PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_ED512),
        ];

        return PublicKeyCredentialCreationOptions::create(
            rp: $rp,
            user: $user,
            challenge: $challenge,
            pubKeyCredParams: $publicKeyCredentialParametersList
        )
            ->setTimeout(config('webauthn.challenge.timeout', 13000))
            ->excludeCredentials()
            ->setAuthenticatorSelection(AuthenticatorSelectionCriteria::create())
            ->setAttestation(PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_NONE);
    }
}
