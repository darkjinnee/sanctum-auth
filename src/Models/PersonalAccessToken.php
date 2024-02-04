<?php

namespace Darkjinnee\SanctumAuth\Models;

use Darkjinnee\SanctumAuth\Contracts\PersonalAccessToken as PersonalAccessTokenContract;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

/**
 * Class PersonalAccessToken
 *
 * @property string $id
 * @property string $tokenable_type
 * @property string $tokenable_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Client $client
 * @property BelongsTo $parent
 * @property HasOne $child
 *
 * @mixin Builder
 */
class PersonalAccessToken extends SanctumPersonalAccessToken implements PersonalAccessTokenContract
{
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'expires_at',
    ];

    /**
     * @return HasOne
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'token_id');
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return hasOne
     */
    public function child(): hasOne
    {
        return $this->hasOne(self::class, 'parent_id', 'id');
    }

    /**
     * @param  string  $name
     * @param  array<string>  $abilities
     * @param  DateTimeInterface|null  $expiresAt
     * @return NewAccessToken
     */
    public function createChild(string $name, array $abilities = ['*'], DateTimeInterface $expiresAt = null): NewAccessToken
    {
        $token = $this->child()->create([
            'tokenable_type' => $this->tokenable_type,
            'tokenable_id' => $this->tokenable_id,
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
        ]);

        return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    }
}
