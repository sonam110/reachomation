<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'fetch-plan', 'fetch-plans','subscribe','delete_gmail','delete_outlook_mail',
        'gdriveupload','onedriveupload','domain-upload','get_outlook_data','get_gmail_data',
        'add-card-model','get-state','subscription-create','stripe-webhook','campaign-list',
        'click-read-all-notification'
    ];
}
