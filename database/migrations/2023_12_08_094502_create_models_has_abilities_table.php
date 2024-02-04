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
        Schema::create('models_has_abilities', static function (Blueprint $table) {
            $table->uuidMorphs('model');
            $table->unique([
                'ability_id',
                'model_type',
                'model_id',
            ], 'models_has_abilities_unique');
            $table->foreignUuid('ability_id')
                ->constrained('abilities')
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
        Schema::dropIfExists('models_has_abilities');
    }
};
