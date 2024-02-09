<?php

namespace Darkjinnee\SanctumAuth\Models;

use App\Models\User;
use Darkjinnee\SanctumAuth\Contracts\AbilityGroup as AbilityGroupContract;
use Darkjinnee\SanctumAuth\Traits\HasAbility;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * Class AbilityGroup
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin Builder
 */
class AbilityGroup extends Model implements AbilityGroupContract
{
    use HasFactory, HasUuids, HasAbility;

    /**
     * @var string
     */
    protected $table = 'ability_groups';

    /**
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

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
     * @return MorphToMany|null
     */
    public function users(): ?MorphToMany
    {
        $modelClass = config('sanctum-auth.classes.user_model', User::class);
        if (! empty($modelClass)) {
            return $this->morphedByMany($modelClass,
                'model',
                'models_has_ability_groups');
        }

        return null;
    }
}
