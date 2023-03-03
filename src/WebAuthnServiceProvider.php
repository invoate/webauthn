<?php

namespace Invoate\WebAuthn;

use Illuminate\Contracts\Foundation\Application;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Webauthn\AttestationStatement\AndroidKeyAttestationStatementSupport;
use Webauthn\AttestationStatement\AppleAttestationStatementSupport;
use Webauthn\AttestationStatement\AttestationObjectLoader;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\FidoU2FAttestationStatementSupport;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AttestationStatement\PackedAttestationStatementSupport;
use Webauthn\AttestationStatement\TPMAttestationStatementSupport;
use Webauthn\AuthenticationExtensions\ExtensionOutputCheckerHandler;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\PublicKeyCredentialLoader;

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

    public function registeringPackage(): void
    {
        $this->app->bind(AttestationStatementSupportManager::class, function (Application $app) {
            $manager = AttestationStatementSupportManager::create();
            $manager->add($app->make(NoneAttestationStatementSupport::class));
            $manager->add($app->make(FidoU2FAttestationStatementSupport::class));
            $manager->add($app->make(AndroidKeyAttestationStatementSupport::class));
            $manager->add($app->make(TPMAttestationStatementSupport::class));
            $manager->add($app->make(PackedAttestationStatementSupport::class));
            $manager->add($app->make(AppleAttestationStatementSupport::class));

            return $manager;
        });

        $this->app->bind(AttestationObjectLoader::class, function (Application $app) {
            return new AttestationObjectLoader($app[AttestationStatementSupportManager::class]);
        });

        $this->app->bind(PublicKeyCredentialLoader::class, function (Application $app) {
            return new PublicKeyCredentialLoader($app[AttestationObjectLoader::class]);
        });

        $this->app->bind(AuthenticatorAttestationResponseValidator::class, function (Application $app) {
            return new AuthenticatorAttestationResponseValidator(
                $app[AttestationStatementSupportManager::class],
                $app[PublicKeyCredential::class],
                null,
                $app[ExtensionOutputCheckerHandler::class]
            );
        });
    }
}
