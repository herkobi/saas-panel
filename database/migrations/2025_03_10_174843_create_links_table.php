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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('url', 2048);
            $table->string('alias')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('password')->nullable();
            $table->string('password_hint')->nullable();
            $table->foreignId('space_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('disabled')->default(false);
            $table->string('expiration_url', 2048)->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->integer('expiration_clicks')->nullable();
            $table->integer('target_type')->default(0);
            $table->integer('goal')->nullable();
            $table->json('country_target')->nullable();
            $table->json('platform_target')->nullable();
            $table->json('language_target')->nullable();
            $table->json('rotation_target')->nullable();
            $table->integer('last_rotation')->default(0);
            $table->integer('clicks')->default(0);
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->foreignId('campaign_id')->nullable();
            $table->timestamps();

            // İndeksler
            $table->index('tenant_id');
            $table->index('space_id');
            $table->index('disabled');
            $table->index('created_at');
            $table->index(['tenant_id', 'disabled']);
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
