<?php

namespace Darkjinnee\SanctumAuth\Console\Commands;

use Darkjinnee\SanctumAuth\Models\Ability;
use Illuminate\Console\Command;

/**
 * Class CreateAbility
 */
class CreateAbility extends Command
{
    /**
     * @const array<string>
     */
    protected const ARGS = [
        '{mask : Route name mask}',
        '{--name= : Ability name }',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum-auth:ability:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create ability';

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
        $mask = $this->argument('mask');
        $name = $this->option('name');
        $ability = Ability::firstOrCreate([
            'mask' => $mask,
            'name' => $name,
        ]);

        $this->table(
            [
                'id',
                'mask',
                'name',
                'created_at',
                'updated_at',
            ],
            [[
                $ability->id,
                $ability->mask,
                $ability->name,
                $ability->created_at,
                $ability->updated_at,
            ]]
        );
        $this->info('Done...');

        return Command::SUCCESS;
    }
}
