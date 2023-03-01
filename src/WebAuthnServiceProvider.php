<?php

namespace Invoate\WebAuthn;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Invoate\WebAuthn\Commands\WebAuthnCommand;

class WebAuthnServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('webauthn')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_webauthn_table')
            ->hasCommand(WebAuthnCommand::class);
    }
}
