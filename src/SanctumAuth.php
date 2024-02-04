<?php

namespace Darkjinnee\SanctumAuth;

use Illuminate\Support\Facades\Request;

class SanctumAuth
{
    /**
     * @return array<string, mixed>
     */
    public static function getClient(): array
    {
        return [
            'user_agent' => Request::userAgent(),
            'ip_address' => Request::ip(),
            'cookie' => Request::cookie(),
        ];
    }
}
