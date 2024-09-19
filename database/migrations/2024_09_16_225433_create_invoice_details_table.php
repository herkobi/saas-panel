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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('title');
            $table->string('invoiceName');
            $table->string('taxOffice')->nullable();
            $table->string('taxNumber')->unique();
            $table->text('address');
            $table->string('zipCode')->nullable();
            $table->string('state')->nullable();
            $table->string('city');
            $table->string('country');
            $table->string('mersis')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('kep')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
