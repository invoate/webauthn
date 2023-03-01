<?php

namespace Invoate\WebAuthn\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    public $signature = 'webauthn:install';

    public $description = 'Install Invoate/WebAuthn';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
