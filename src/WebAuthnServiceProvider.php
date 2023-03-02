<?php

namespace Invoate\WebAuthn;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WebAuthnServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('webauthn')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('webauthn')
            ->hasMigration('create_credentials_table');
    }
}
