<?php
// database/migrations/XXXX_XX_XX_create_tenants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_id')->nullable();
            $table->string('code')->unique();
            $table->string('domain')->nullable()->unique();
            $table->boolean('has_domain')->default(false);
            $table->integer('status');
            $table->foreignId('first_plan')->nullable(); // İlk seçilen plan
            $table->foreignId('first_paid_plan')->nullable(); // İlk ücretli plan
            $table->boolean('new_tenant')->default(true); // Yeni tenant mi?
            $table->json('settings')->nullable();
            $table->string('storage_folder');
            $table->timestamps();

            $table->index(['code', 'domain']);
            $table->foreign('group_id')->references('id')->on('account_groups')->restrictOnDelete();
        });

        // Users tablosuna tenant_id ekleme
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('tenant_id')->nullable()->after('id');
            $table->boolean('is_tenant_owner')->default(false)->after('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::dropIfExists('tenants');
    }
};
