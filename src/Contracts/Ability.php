<?php

namespace Darkjinnee\SanctumAuth\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Interface Ability
 */
interface Ability
{
    /**
     * @param  string  $mask
     * @return Model|null
     */
    public static function findByMask(string $mask): ?Model;

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
    public function abilityGroups(): MorphToMany;

    /**
     * @return MorphToMany|null
     */
    public function users(): ?MorphToMany;
}
