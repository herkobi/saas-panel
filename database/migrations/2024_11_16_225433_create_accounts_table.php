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
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('invoice_name')->unique();
            $table->string('tax_office')->nullable();
            $table->string('tax_number')->nullable()->unique();
            $table->text('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('district')->nullable();
            $table->foreignUuid('state_id')->nullable()->constrained('states')->onDelete('set null');
            $table->foreignUuid('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->string('mersis')->nullable();
            $table->string('phone')->nullable();
            $table->string('gsm')->nullable();
            $table->string('email')->nullable();
            $table->string('kep')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
