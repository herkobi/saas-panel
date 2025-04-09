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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('message');
            $table->json('log')->nullable();
            $table->timestamps();

            // Tekil indeksler
            $table->index('tenant_id');
            $table->index('message');
            $table->index('created_at');

            // Bileşik indeksler
            $table->index(['tenant_id', 'message']);
            $table->index(['tenant_id', 'created_at']);
            $table->index(['tenant_id', 'message', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
