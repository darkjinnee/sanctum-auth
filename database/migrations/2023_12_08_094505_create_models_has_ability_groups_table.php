<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('models_has_ability_groups', static function (Blueprint $table) {
            $table->uuidMorphs('model');
            $table->unique([
                'ability_group_id',
                'model_type',
                'model_id',
            ], 'models_has_ability_groups_unique');
            $table->foreignUuid('ability_group_id')
                ->constrained('ability_groups')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('models_has_ability_groups');
    }
};
