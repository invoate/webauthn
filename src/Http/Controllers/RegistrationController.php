<?php

namespace Invoate\WebAuthn\Http\Controllers;

use Illuminate\Routing\Controller;
use Invoate\WebAuthn\Http\Requests\RegistrationOptionsRequest;
use Invoate\WebAuthn\Http\Requests\RegistrationRequest;

class RegistrationController extends Controller
{
    public function generateOptions(RegistrationOptionsRequest $request)
    {
    }

    public function verifyRegistration(RegistrationRequest $request)
    {
    }
}
