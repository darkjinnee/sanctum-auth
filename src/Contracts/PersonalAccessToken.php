<?php

namespace Darkjinnee\SanctumAuth\Contracts;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Interface PersonalAccessToken
 */
interface PersonalAccessToken
{
    /**
     * @return HasOne
     */
    public function client(): HasOne;
}
