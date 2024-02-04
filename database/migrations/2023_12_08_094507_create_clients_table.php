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
        Schema::create('clients', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuidMorphs('model');
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('cookie')->nullable();
            $table->foreignId('token_id')
                ->constrained('personal_access_tokens')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
