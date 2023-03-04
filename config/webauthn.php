<?php

// config for Invoate/WebAuthn
return [

    'challenge' => [
        'timeout' => 300000,
        'bytes' => 64,
    ],

    'registration' => [
        'session-key' => 'webauthn',
    ],

    'authentication' => [
        'session-key' => 'webauthn',
    ],

];
