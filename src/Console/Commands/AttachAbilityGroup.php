<?php

namespace Darkjinnee\SanctumAuth\Console\Commands;

use Darkjinnee\SanctumAuth\Models\AbilityGroup;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class AttachAbilityGroup
 */
class AttachAbilityGroup extends Command
{
    /**
     * @var array<string>
     */
    protected const ARGS = [
        '{id : Model ID to manage ability group (example: 1)}',
        '{--group-id=* : Ability group IDs (example: --group-id=1 --group-id=2)}',
        '{--type= : Model class namespace to manage ability group}',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum-auth:ability-group:attach';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach ability groups to modal';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->signature .= ' '.implode(self::ARGS);
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @noinspection DuplicatedCode
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     */
    public function handle(): int
    {
        $id = $this->argument('id');
        $options = $this->options();

        $modelClass = $options['type'] ?? config('sanctum-auth.classes.user_model');

        try {
            $model = $modelClass::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        $dirtyGroupIds = $options['group-id'];
        $groups = AbilityGroup::find($dirtyGroupIds)->diff($model->abilityGroups);
        $groupIds = $groups->pluck('id')->all();

        $model->attachAbilityGroup($groupIds);
        $model->load('abilityGroups');
        $groups = $model->abilityGroups->map(function ($item) {
            return [
                $item->id,
                $item->name,
                $item->description,
                $item->created_at,
                $item->updated_at,
            ];
        });
        $this->table(
            [
                'id',
                'name',
                'description',
                'created_at',
                'updated_at',
            ], $groups
        );

        $groupIdsDiff = array_diff(
            $dirtyGroupIds,
            $groupIds,
            $model->abilities->pluck('id')->all()
        );

        if (count($groupIdsDiff) > 0) {
            $this->warn('Some ability groups not found: '.implode(',', $groupIdsDiff));
        }
        $this->info('Done...');

        return Command::SUCCESS;
    }
}
