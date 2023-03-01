<?php

use App\Http\Controllers\WebAuthn\AuthenticationController;
use App\Http\Controllers\WebAuthn\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::controller(RegistrationController::class)->group(function () {
        Route::post('webauthn/register/options', 'generateOptions')->name('webauthn.register.options');
        Route::post('webauthn/register', 'verifyRegistration')->name('webauthn.register');
    });

    Route::controller(AuthenticationController::class)->group(function () {
        Route::post('webauthn/authenticate/options', 'generateOptions')->name('webauthn.authenticate.options');
        Route::post('webauthn/authenticate', 'verifyAuthentication')->name('webauthn.authenticate');
    });
});
