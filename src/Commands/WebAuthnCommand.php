<?php

namespace Invoate\WebAuthn\Commands;

use Illuminate\Console\Command;

class WebAuthnCommand extends Command
{
    public $signature = 'webauthn';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
