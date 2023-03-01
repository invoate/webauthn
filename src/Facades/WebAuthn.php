<?php

namespace Invoate\WebAuthn\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Invoate\WebAuthn\WebAuthn
 */
class WebAuthn extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Invoate\WebAuthn\WebAuthn::class;
    }
}
