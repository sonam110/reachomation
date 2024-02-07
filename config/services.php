<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_MAIL_ACCESS_KEY_ID'),
        'secret' => env('AWS_MAIL_SECRET_ACCESS_KEY'),
        'region' => env('AWS_MAIL_DEFAULT_REGION', 'ap-south-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID','439830056376-jjjren131k7mig3v6dvrlhtociibu5k8.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_SECRET','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'),
        'redirect' => env('GOOGLE_REDIRECT_URL','https://reachomation.com/google/callback'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
