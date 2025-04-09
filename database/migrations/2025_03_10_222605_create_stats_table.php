<?php

use App\Enums\StatType;
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
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('value');
            $table->integer('count')->default(1);
            $table->date('date');

            // İndeksler
            $table->index('link_id');
            $table->index(['link_id', 'name']);
            $table->index(['link_id', 'date']);
            $table->index(['link_id', 'name', 'date']);

            // Benzersiz kısıtlama - aynı link için aynı günde aynı tip ve değere sahip birden fazla kayıt olmamalı
            $table->unique(['link_id', 'name', 'value', 'date'], 'stats_unique_constraint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
