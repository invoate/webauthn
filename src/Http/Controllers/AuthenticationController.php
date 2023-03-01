<?php

namespace Invoate\WebAuthn\Http\Controllers;

use Illuminate\Routing\Controller;
use Invoate\WebAuthn\Http\Requests\AuthenticationOptionsRequest;
use Invoate\WebAuthn\Http\Requests\AuthenticationRequest;

class AuthenticationController extends Controller
{
    public function generateOptions(AuthenticationOptionsRequest $request)
    {
    }

    public function verifyAuthentication(AuthenticationRequest $request)
    {
    }
}
