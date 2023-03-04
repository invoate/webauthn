<?php

namespace Invoate\WebAuthn\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface WebAuthnticatable
{
    public function credentials(): MorphMany;
}
