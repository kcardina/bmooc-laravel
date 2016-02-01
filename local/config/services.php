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
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    
    /*
    'twitter' => [
	    'client_id' => 'wwaRMHz98jgkkXqBJsMHXSjIL',
	    'client_secret' => '2G9SPoJm162JQudPeJzkuNStq0E6EUJzmDcZdJgBtpKeyQZyQr',
	    'redirect' => 'http://bmooc.local/login/twitter',
	    //'redirect' => 'http://localhost/bMoocLaravel/public/auth/twitter/callback',
    ],
     */

];

//oauth_token=HgcoswAAAAAAhl_iAAABT9-4kSM&oauth_verifier=909U2PhaECPCVCMpUzVXi3h4j2Nr8Sl7
