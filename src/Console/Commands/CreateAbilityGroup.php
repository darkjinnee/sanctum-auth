<?php

namespace Darkjinnee\SanctumAuth\Console\Commands;

use Darkjinnee\SanctumAuth\Models\AbilityGroup;
use Illuminate\Console\Command;

/**
 * Class CreateAbilityGroup
 */
class CreateAbilityGroup extends Command
{
    /**
     * @var array<string>
     */
    protected const ARGS = [
        '{name : Group name}',
        '{--description= : Group description}',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum-auth:ability-group:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create ability group';

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
        $name = $this->argument('name');
        $description = $this->option('description');
        $abilityGroup = AbilityGroup::firstOrCreate([
            'name' => $name,
            'description' => $description,
        ]);

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
