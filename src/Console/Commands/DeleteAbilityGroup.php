<?php

namespace Darkjinnee\SanctumAuth\Console\Commands;

use Darkjinnee\SanctumAuth\Models\AbilityGroup;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class DeleteAbilityGroup
 */
class DeleteAbilityGroup extends Command
{
    /**
     * @const array<string>
     */
    protected const ARGS = [
        '{id : Group ID}',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum-auth:ability-group:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete ability group';

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
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     */
    public function handle(): int
    {
        $id = $this->argument('id');

        try {
            $abilityGroup = AbilityGroup::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        $abilityGroup->delete();
        $this->table(
            [
                'id',
                'name',
                'description',
                'created_at',
                'updated_at',
            ],
            [[
                $abilityGroup->id,
                $abilityGroup->name,
                $abilityGroup->description,
                $abilityGroup->created_at,
                $abilityGroup->updated_at,
            ]]
        );
        $this->info('Done...');

        return Command::SUCCESS;
    }
}
