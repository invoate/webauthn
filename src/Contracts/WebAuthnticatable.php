<?php

namespace Invoate\WebAuthn\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface WebAuthnticatable
{
    public function credentials(): HasMany;
}
