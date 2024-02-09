<?php

use App\Models\User;
use Darkjinnee\SanctumAuth\Http\Requests\RegisterRequest;
use Darkjinnee\SanctumAuth\Http\Requests\TokenRequest;

return [

    /** Usernames developers */
    'developers' => ['test@gmail.com'],

    /** Default ability masks for access token */
    'masks' => ['api.auth.*'],

    /** Client verification mode from 0 to 3 */
    'verify' => [
        'enable' => true,
        'mode' => 0,
    ],

    /** Refresh token */
    'refresh_token' => [
        'enable' => true,
        'expiration' => null,
        'masks' => ['api.auth.refresh'],
    ],

    /** Auth fields for check */
    'fields' => [
        'username' => 'email',
        'password' => 'password',
    ],

    /** Classes */
    'classes' => [
        'register_request' => RegisterRequest::class,
        'token_request' => TokenRequest::class,
        'user_model' => User::class,
    ],
];
