<?php

namespace Darkjinnee\SanctumAuth\Console\Commands;

use Darkjinnee\SanctumAuth\Models\Ability;
use Illuminate\Console\Command;

/**
 * Class ListAbility
 */
class ListAbility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum-auth:ability:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List ability';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $abilities = Ability::all()->map(function ($item) {
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
        $this->info('Done...');

        return Command::SUCCESS;
    }
}
