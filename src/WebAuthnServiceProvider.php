<?php

namespace Invoate\WebAuthn;

use Invoate\WebAuthn\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasRoute('webauthn')
            ->hasMigration('create_webauthn_table')
            ->hasCommand(InstallCommand::class);
    }
}
