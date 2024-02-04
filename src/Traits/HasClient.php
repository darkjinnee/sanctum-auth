<?php

namespace Darkjinnee\SanctumAuth\Traits;

use Darkjinnee\SanctumAuth\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasClient
 */
trait HasClient
{
    /**
     * @param  int  $tokenId
     * @param  array<string, mixed>  $client
     * @return Model
     */
    public function createClient(int $tokenId, array $client): Model
    {
        return $this->clients()->create([
            'token_id' => $tokenId,
            'user_agent' => $client['user_agent'],
            'ip_address' => $client['ip_address'],
            'cookie' => $client['cookie'],
        ]);
    }

    /**
     * @return MorphMany
     */
    public function clients(): MorphMany
    {
        return $this->morphMany(Client::class, 'model');
    }

    /**
     * @return ?Client
     */
    public function currentClient(): ?Client
    {
        /** @phpstan-ignore-next-line */
        return $this->currentAccessToken()->client ?? null;
    }
}
