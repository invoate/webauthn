<?php

namespace Invoate\WebAuthn\Actions;

use Invoate\WebAuthn\Contracts\WebAuthnticatable;
use Invoate\WebAuthn\Models\Credential;
use Webauthn\PublicKeyCredentialSource;

class CreateNewCredential
{
    public function __invoke(WebAuthnticatable $user, $data, PublicKeyCredentialSource $credential): Credential
    {
        return $user->credentials()->forceCreate([
            'name' => null,
            'rp_id' => null,
            'origin' => null,
            'transports' => $credential->getTransports(),
            'extension' => null,
            'attachment' => null,
            'aaguid' => $credential->getAaguid(),
            'credential_id' => $credential->getPublicKeyCredentialId(),
            'public_key' => $credential->getCredentialPublicKey(),
            'attestation_type' => $credential->getAttestationType(),
            'certificates' => null,
            'last_used_at' => null,
        ]);
    }
}
