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
        Schema::create('tax_regions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tax_id');
            $table->uuid('country_id');
            $table->uuid('state_id')->nullable(); // Eyalet/Şehir ID'si - opsiyonel
            $table->timestamps();

            // İlişkiler
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');

            // İndexler
            $table->index(['tax_id', 'country_id']);
            $table->index(['tax_id', 'state_id']);

            // Aynı vergi için aynı ülke-eyalet kombinasyonu olmamalı
            $table->unique(['tax_id', 'country_id', 'state_id'], 'unique_tax_region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_regions');
    }
};
