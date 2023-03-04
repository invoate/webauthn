<?php

namespace Invoate\WebAuthn\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Invoate\WebAuthn\Models\Credential;

trait WebAuthntication
{
    public function credentials(): MorphMany
    {
        return $this->MorphMany(Credential::class, 'webauthnticatable');
    }
}
