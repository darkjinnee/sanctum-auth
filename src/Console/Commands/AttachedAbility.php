<?php

namespace Darkjinnee\SanctumAuth\Console\Commands;

use Darkjinnee\SanctumAuth\Models\AbilityGroup;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class AttachedAbility
 */
class AttachedAbility extends Command
{
    /**
     * @var array<string>
     */
    protected const ARGS = [
        '{id : Model ID (example: 1)}',
        '{--type='.AbilityGroup::class.' : Model class namespace to manage ability}',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum-auth:ability:attached:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of model ability';

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
     * @noinspection PhpUndefinedMethodInspection
     */
    public function handle(): int
    {
        $id = $this->argument('id');
        $type = $this->option('type');

        try {
            $model = $type::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

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
        $this->info('Done...');

        return Command::SUCCESS;
    }
}
