<?php

namespace Darkjinnee\SanctumAuth\Traits;

use Darkjinnee\SanctumAuth\Models\AbilityGroup;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Trait HasAbilityGroup
 */
trait HasAbilityGroup
{
    /**
     * @param  array<string>  $abilityGroupIds
     * @return void
     */
    public function attachAbilityGroup(array $abilityGroupIds = []): void
    {
        $this->abilityGroups()->attach($abilityGroupIds);
    }

    /**
     * @return MorphToMany
     */
    public function abilityGroups(): MorphToMany
    {
        return $this->morphToMany(AbilityGroup::class,
            'model',
            'models_has_ability_groups');
    }

    /**
     * @param  array<string>  $abilityGroupIds
     * @return int
     */
    public function detachAbilityGroup(array $abilityGroupIds = []): int
    {
        return $this->abilityGroups()->detach($abilityGroupIds);
    }

    /**
     * @param  array<string>  $abilityGroupIds
     * @return array<string>
     */
    public function syncAbilityGroup(array $abilityGroupIds = []): array
    {
        return $this->abilityGroups()->sync($abilityGroupIds);
    }
}
