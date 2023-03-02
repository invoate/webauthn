<?php

namespace Invoate\WebAuthn\Http\Controllers;

use Cose\Algorithms;
use Illuminate\Routing\Controller;
use Invoate\WebAuthn\Http\Requests\RegistrationOptionsRequest;
use Invoate\WebAuthn\Http\Requests\RegistrationRequest;
use Invoate\WebAuthn\PublicKeyCredential;
use Webauthn\AttestationStatement\AttestationObjectLoader;
use Webauthn\AttestationStatement\AttestationStatement;
use Webauthn\AttestationStatement\AttestationStatementSupport;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AuthenticationExtensions\ExtensionOutputCheckerHandler;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialLoader;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialUserEntity;

class RegistrationController extends Controller
{
    public function generateOptions(RegistrationOptionsRequest $request)
    {
        return $this->publicKeyCredentialCreationOptions();
    }

    public function verifyRegistration(RegistrationRequest $request, PublicKeyCredentialLoader $loader)
    {

        $manager = AttestationStatementSupportManager::create();
        $manager->add(NoneAttestationStatementSupport::create());

        $loader = AttestationObjectLoader::create($manager);
        $publicKeyCredentialLoader = PublicKeyCredentialLoader::create($loader);

        $data = $request->all();

        $publicKeyCredential = $publicKeyCredentialLoader->loadArray($data);

        $authenticatorAttestationResponse = $publicKeyCredential->getResponse();
        if (!$authenticatorAttestationResponse instanceof AuthenticatorAttestationResponse) {
            //e.g. process here with a redirection to the public key creation page.
        }

        $publicKeyCredentialSource = new PublicKeyCredential;
        $extensionOutputCheckerHandler = ExtensionOutputCheckerHandler::create();
        $authenticatorAttestationResponseValidator = AuthenticatorAttestationResponseValidator::create(
            attestationStatementSupportManager: $manager,
            publicKeyCredentialSource: $publicKeyCredentialSource,
            tokenBindingHandler: null,
            extensionOutputCheckerHandler: $extensionOutputCheckerHandler
        );


        $publicKeyCredentialSource = $authenticatorAttestationResponseValidator->check(
            authenticatorAttestationResponse: $authenticatorAttestationResponse,
            publicKeyCredentialCreationOptions: $this->publicKeyCredentialCreationOptions(),
            request: 'coordina.test'
        );

        dd($publicKeyCredentialSource);
        dd($publicKeyCredential);
    }

    protected function publicKeyCredentialCreationOptions()
    {
        $rp = PublicKeyCredentialRpEntity::create(
            name: "Testing",
            id: "coordina.test"
        );

        $user = PublicKeyCredentialUserEntity::create(
            name: "Username",
            id: "123",
            displayName: "User Name"
        );

        $challenge = "12345678987654321";

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
            ->setTimeout(30_000)
            ->excludeCredentials()
            ->setAuthenticatorSelection(AuthenticatorSelectionCriteria::create())
            ->setAttestation(PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_NONE);
    }
}
