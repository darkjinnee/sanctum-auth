<?php

namespace Darkjinnee\SanctumAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class AttachedAbilityGroup
 */
class AttachedAbilityGroup extends Command
{
    /**
     * @const array<string>
     */
    protected const ARGS = [
        '{id : Model ID to manage ability group (example: 1)}',
        '{--type= : Model class namespace to manage ability group}',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum-auth:ability-group:attached:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of model ability groups';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $id = $this->argument('id');
        $type = $this->option('type');

        if (! $type) {
            $type = config('sanctum-auth.classes.user');
        }

        try {
            $model = $type::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

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
        $this->info('Done...');

        return Command::SUCCESS;
    }
}
