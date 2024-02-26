<?php

namespace Darkjinnee\SanctumAuth\Console\Commands;

use Darkjinnee\SanctumAuth\Models\AbilityGroup;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class DetachAbility
 */
class DetachAbility extends Command
{
    /**
     * @var array<string>
     */
    protected const ARGS = [
        '{id : Model ID to manage ability (example: 1)}',
        '{--ability-id=* : Ability IDs (example: --ability-id=1 --ability-id=2)}',
        '{--type='.AbilityGroup::class.' : Model class namespace to manage ability}',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum-auth:ability:detach';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detach ability to model';

    /**
     * Create a new command instance.
     *
     * @return void
     * @noinspection DuplicatedCode
     */
    public function __construct()
    {
        $this->signature .= ' '.implode(self::ARGS);
        parent::__construct();
    }

    /**
     * @return int
     * @noinspection DuplicatedCode
     */
    public function handle(): int
    {
        $id = $this->argument('id');
        $options = $this->options();

        try {
            $model = $options['type']::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        $dirtyAbilityIds = $options['ability-id'];
        $abilities = $model->abilities->only($dirtyAbilityIds);
        $abilityIds = $abilities->pluck('id')->all();

        $model->detachAbility($abilityIds);
        $model->load('abilities');
        $abilities = $model->abilities->map(function ($item) {
            return [
                $item->id,
                $item->mask,
                $item->name,
                $item->created_at,
                $item->updated_at,
            ];
        });
        $this->table(
            [
                'id',
                'mask',
                'name',
                'created_at',
                'updated_at',
            ], $abilities
        );

        $abilityIdsDiff = array_diff(
            $dirtyAbilityIds,
            $abilityIds,
            $model->abilities->pluck('id')->all()
        );

        if (count($abilityIdsDiff) > 0) {
            $this->warn('Some abilities not found: '.implode(',', $abilityIdsDiff));
        }
        $this->info('Done...');

        return Command::SUCCESS;
    }
}
