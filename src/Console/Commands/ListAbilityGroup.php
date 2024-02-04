<?php

namespace Darkjinnee\SanctumAuth\Console\Commands;

use Darkjinnee\SanctumAuth\Models\AbilityGroup;
use Illuminate\Console\Command;

/**
 * Class ListAbilityGroup
 */
class ListAbilityGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum-auth:ability-group:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List ability group';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $groups = AbilityGroup::all()->map(function ($item) {
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
