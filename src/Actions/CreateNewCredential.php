<?php

namespace Invoate\WebAuthn\Actions;

use Invoate\WebAuthn\Contracts\WebAuthnticatable;
use Invoate\WebAuthn\Models\Credential;
use Webauthn\PublicKeyCredentialSource;

class CreateNewCredential
{
    public function __invoke(WebAuthnticatable $user, array $data, PublicKeyCredentialSource $credential, PublicKeyCredentialCreationOptions $publicKeyCredentialCreationOptions): Credential
    {
        return $user->credentials()->forceCreate([
            'name' => null,
            'rp_id' => $publicKeyCredentialCreationOptions->getRp()->getId(),
            'transports' => $data['response']['transports'] ?? null,
            'extension' => $data['clientExtensionResults'] ?? null,
            'attachment' => $data['authenticatorAttachment'] ?? null,
            'aaguid' => $credential->getAaguid(),
            'credential_id' => $credential->getPublicKeyCredentialId(),
            'public_key' => $credential->getCredentialPublicKey(),
            'attestation_type' => $credential->getAttestationType(),
            'certificates' => null,
            'last_used_at' => null,
        ]);
    }
}
