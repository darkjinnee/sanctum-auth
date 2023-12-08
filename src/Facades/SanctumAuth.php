<?php

namespace Darkjinnee\SanctumAuth\Facades;

use Illuminate\Support\Facades\Facade;

class SanctumAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'sanctum-auth';
    }
}
