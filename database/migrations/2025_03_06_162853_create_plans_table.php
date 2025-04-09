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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_free')->default(false);
            $table->enum('billing_period', ['monthly', 'yearly', 'both'])->default('both');
            $table->string('country_code')->nullable(); // Nullable yapıldı
            $table->string('currency_code')->nullable(); // Nullable yapıldı
            $table->string('tax_rate_code')->nullable(); // Nullable yapıldı
            $table->decimal('monthly_price', 10, 2)->nullable();
            $table->decimal('yearly_price', 10, 2)->nullable();
            $table->integer('trial_days')->default(0);
            $table->integer('grace_period_days')->default(0);
            $table->enum('payment_timing', ['upfront', 'later'])->default('upfront');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
