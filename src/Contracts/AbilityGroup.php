<?php

namespace Darkjinnee\SanctumAuth\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Interface AbilityGroup
 */
interface AbilityGroup
{
    /**
     * @param  string  $name
     * @return Model|null
     */
    public static function findByName(string $name): ?Model;

    /**
     * @param  string  $id
     * @return Model|null
     */
    public static function findById(string $id): ?Model;

    /**
     * @return MorphToMany
     */
    public function abilities(): MorphToMany;

    /**
     * @return MorphToMany|null
     */
    public function users(): ?MorphToMany;
}
