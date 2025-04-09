<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('feature_id')->constrained()->onDelete('cascade');
            $table->enum('access_type', ['limited', 'access_only'])->default('access_only');
            $table->enum('limit_type', ['renewable', 'cumulative'])->nullable();
            $table->integer('limit_value')->nullable();
            $table->enum('limit_reset_period', ['hourly', 'daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->boolean('restore_on_delete')->default(false);
            $table->timestamps();

            // Bir plan için bir özellik sadece bir kez tanımlanabilir
            $table->unique(['plan_id', 'feature_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_features');
    }
};
