<?php

namespace Darkjinnee\SanctumAuth\Traits;

use Illuminate\Support\Collection;

/**
 * Trait HasSanctumAuth
 */
trait HasSanctumAuth
{
    use HasAbility, HasAbilityGroup, HasClient;

    /**
     * @return array<string>
     */
    public function masks(): array
    {
        $developers = config('sanctum-auth.developers', []);
        if (in_array($this['email'], $developers, true)) {
            return ['*'];
        }

        $default = config('sanctum-auth.masks', [
            'api.auth.*',
        ]);

        return $this->abilityMasks()->merge($default)->all();
    }

    /**
     * @noinspection PhpUndefinedFieldInspection
     *
     * @return Collection
     */
    public function abilityMasks(): Collection
    {
        /** @phpstan-ignore-next-line */
        $masks = collect();
        $masks->push($this->abilities->pluck('mask'));

        $this->abilityGroups->each(function ($item) use ($masks) {
            $masks->push($item->abilities->pluck('mask'));
        });

        $masks = $masks->flatten();
        $duplicatesKeys = $masks->duplicates()->keys();

        return $masks->except($duplicatesKeys)->values();
    }
}
