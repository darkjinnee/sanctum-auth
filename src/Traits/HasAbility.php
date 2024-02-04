<?php

namespace Darkjinnee\SanctumAuth\Traits;

use Darkjinnee\SanctumAuth\Models\Ability;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Trait HasAbility
 */
trait HasAbility
{
    /**
     * @param  array<string>  $abilityIds
     * @return void
     */
    public function attachAbility(array $abilityIds = []): void
    {
        $this->abilities()->attach($abilityIds);
    }

    /**
     * @return MorphToMany
     */
    public function abilities(): MorphToMany
    {
        return $this->morphToMany(Ability::class,
            'model',
            'models_has_abilities');
    }

    /**
     * @param  array<string>  $abilityIds
     * @return int
     */
    public function detachAbility(array $abilityIds = []): int
    {
        return $this->abilities()->detach($abilityIds);
    }

    /**
     * @param  array<string>  $abilityIds
     * @return array<string>
     */
    public function syncAbility(array $abilityIds = []): array
    {
        return $this->abilities()->sync($abilityIds);
    }
}
