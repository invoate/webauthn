<?php

use Illuminate\Support\Facades\Route;
use Invoate\WebAuthn\Http\Controllers\AuthenticationController;
use Invoate\WebAuthn\Http\Controllers\RegistrationController;

Route::middleware('web')->group(function () {
    Route::controller(RegistrationController::class)->group(function () {
        Route::match(['get', 'post'], 'webauthn/register/options', 'generateOptions')->name('webauthn.register.options');
        Route::post('webauthn/register', 'verifyRegistration')->name('webauthn.register');
    });

    Route::controller(AuthenticationController::class)->group(function () {
        Route::match(['get', 'post'], 'webauthn/authenticate/options', 'generateOptions')->name('webauthn.authenticate.options');
        Route::post('webauthn/authenticate', 'verifyAuthentication')->name('webauthn.authenticate');
    });
});
