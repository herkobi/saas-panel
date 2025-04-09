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
        Schema::create('link_pixel', function (Blueprint $table) {
            $table->id(); // auto_increment primary key
            $table->foreignId('link_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pixel_id')->constrained()->cascadeOnDelete();

            // İki sütun için benzersiz index ekleyin (primary key yerine)
            $table->unique(['link_id', 'pixel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_pixel');
    }
};
