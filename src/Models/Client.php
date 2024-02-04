<?php

namespace Darkjinnee\SanctumAuth\Models;

use Darkjinnee\SanctumAuth\Contracts\Client as ClientContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Class Client
 *
 * @property string $id
 * @property int $token_id
 * @property mixed $user_agent
 * @property mixed $ip_address
 * @property mixed $cookie
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin Builder
 */
class Client extends Model implements ClientContract
{
    use HasFactory, HasUuids;

    /**
     * @var string
     */
    protected $table = 'clients';

    /**
     * @var array<string>
     */
    protected $fillable = [
        'token_id',
        'user_agent',
        'ip_address',
        'cookie',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'cookie' => 'array',
    ];

    /**
     * @noinspection DynamicInvocationViaScopeResolutionInspection
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     *
     * @param  string  $userAgent
     * @return Collection
     */
    public static function findByUserAgent(string $userAgent): Collection
    {
        return static::where('user_agent', $userAgent)->get();
    }

    /**
     * @noinspection DynamicInvocationViaScopeResolutionInspection
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     *
     * @param  string  $ipAddress
     * @return Collection
     */
    public static function findByIpAddress(string $ipAddress): Collection
    {
        return static::where('ip_address', $ipAddress)->get();
    }

    /**
     * @noinspection DynamicInvocationViaScopeResolutionInspection
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     *
     * @param  string  $id
     * @return Model|null
     */
    public static function findById(string $id): ?Model
    {
        return static::find($id);
    }

    /**
     * @noinspection DynamicInvocationViaScopeResolutionInspection
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     *
     * @param  int  $id
     * @return Model|null
     */
    public static function findByTokenId(int $id): ?Model
    {
        return static::where('token_id', $id)->first();
    }

    /**
     * @return BelongsTo
     */
    public function token(): BelongsTo
    {
        return $this->belongsTo(PersonalAccessToken::class);
    }

    /**
     * @return MorphTo
     */
    public function user(): MorphTo
    {
        return $this->morphTo('model');
    }
}
