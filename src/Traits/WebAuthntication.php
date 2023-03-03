<?php

namespace Invoate\WebAuthn\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Invoate\WebAuthn\Models\Credential;

trait WebAuthntication
{
    public function credentials(): HasMany
    {
        return $this->hasMany(Credential::class);
    }
}
