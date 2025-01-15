<?php

use App\Enums\Status;
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
        Schema::create('currencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('status')->default(Status::ACTIVE->value);
            $table->string('name', 50);  // max 50 karakter
            $table->string('symbol', 10);
            $table->string('symbol_position');
            $table->string('thousands_separator', 1); // tek karakter
            $table->string('decimal_separator', 1);   // tek karakter
            $table->integer('decimal_digits')->default(2); // varsayılan 2
            $table->string('iso_code', 3)->unique();
            $table->timestamps();

            $table->index('status');
            $table->index('iso_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
