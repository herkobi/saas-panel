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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('link_id')->nullable()->constrained()->nullOnDelete();

            // Ana alanlar
            $table->string('title');
            $table->string('slug')->unique()->index();
            $table->text('content')->nullable();
            $table->text('terms')->nullable();
            $table->string('external_link', 2048)->nullable();

            // Görünürlük ve durum
            $table->string('status')->index();  // status'e index
            $table->boolean('is_published')->default(false)->index(); // published'a index
            $table->timestamp('published_at')->nullable()->index();
            $table->boolean('is_featured')->default(false)->index(); // featured'a index

            // Tarihler
            $table->timestamp('start_date')->nullable()->index();  // tarihlere index
            $table->timestamp('end_date')->nullable()->index();
            $table->timestamp('apply_date')->nullable()->index();
            $table->string('apply_name')->nullable();

            // Form yönetimi
            $table->boolean('has_form')->default(false);
            //$table->foreignId('form_id')->nullable()->constrained()->nullOnDelete();

            // Sayaçlar
            $table->integer('views')->default(0);

            // Medya
            $table->string('image')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();

            $table->timestamps();

            // Composite indexler
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'is_published']);
            $table->index(['tenant_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
