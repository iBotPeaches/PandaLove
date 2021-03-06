<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => '',
        'secret' => '',
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key'    => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => 'User',
        'secret' => '',
    ],

    'google' => [
        'client_id'     => env('GOOGLE_ID'),
        'client_secret' => env('GOOGLE_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT', 'http://localhost:8000/auth/callback'),
    ],

    'panda' => [
        'group_id' => env('BOT_HANGOUT'),
    ],

    'halo5' => [
        'key' => env('HALO5_KEY'),
    ],

    'xbox' => [
        'key' => env('XBOXAPI_KEY'),
    ],

    'fortnite' => [
        'email'    => env('FORTNITE_USER_EMAIL'),
        'password' => env('FORTNITE_USER_PASSWORD'),
        'launcher' => env('FORTNITE_LAUNCHER_TOKEN'),
        'client'   => env('FORTNITE_CLIENT_TOKEN'),
    ],

];
