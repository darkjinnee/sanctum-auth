<?php

namespace Darkjinnee\SanctumAuth\Console\Commands;

use Darkjinnee\SanctumAuth\Models\Ability;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class DeleteAbility
 */
class DeleteAbility extends Command
{
    /**
     * @const array<string>
     */
    protected const ARGS = [
        '{id : Ability ID}',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum-auth:ability:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete ability';

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
            $ability = Ability::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        $ability->delete();
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
