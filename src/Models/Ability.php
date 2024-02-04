<?php

namespace Darkjinnee\SanctumAuth\Models;

use App\Models\User;
use Darkjinnee\SanctumAuth\Contracts\Ability as AbilityContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * Class Ability
 *
 * @property string $id
 * @property string $mask
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin Builder
 */
class Ability extends Model implements AbilityContract
{
    use HasFactory, HasUuids;

    /**
     * @var string
     */
    protected $table = 'abilities';

    /**
     * @var array<string>
     */
    protected $fillable = [
        'mask',
        'name',
    ];

    /**
     * @noinspection DynamicInvocationViaScopeResolutionInspection
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     *
     * @param  string  $mask
     * @return Model|null
     */
    public static function findByMask(string $mask): ?Model
    {
        return static::where('mask', $mask)->first();
    }

    /**
     * @noinspection DynamicInvocationViaScopeResolutionInspection
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     *
     * @param  string  $name
     * @return Model|null
     */
    public static function findByName(string $name): ?Model
    {
        return static::where('name', $name)->first();
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
     * @return MorphToMany
     */
    public function abilityGroups(): MorphToMany
    {
        return $this->morphedByMany(AbilityGroup::class,
            'model',
            'models_has_abilities');
    }

    /**
     * @return MorphToMany|null
     */
    public function users(): ?MorphToMany
    {
        $userModel = config('sanctum-auth.classes.user', User::class);
        if (! empty($userModel)) {
            return $this->morphedByMany($userModel,
                'model',
                'models_has_abilities');
        }

        return null;
    }
}
