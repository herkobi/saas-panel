<?php

use App\Enums\Status;
use App\Enums\AgreementVersionStatus;
use App\Enums\UserType;
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
        Schema::create('agreements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('status')->default(Status::ACTIVE);
            $table->integer('user_type')->default(UserType::USER);
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('show_on_register')->default(false);
            $table->boolean('show_on_payment')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('agreement_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('status')->default(AgreementVersionStatus::DRAFT);
            $table->uuid('agreement_id');
            $table->string('version');
            $table->longText('content');
            $table->timestamp('published_at')->nullable();
            $table->boolean('require_acceptance')->default(false);
            $table->boolean('block_access')->default(false);
            $table->boolean('send_notification')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('agreement_id')
                  ->references('id')
                  ->on('agreements');

            $table->unique(['agreement_id', 'version']);
        });

        Schema::create('agreement_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('agreement_id');
            $table->uuid('agreement_version_id');
            $table->timestamp('accepted_at');
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('agreement_id')
                  ->references('id')
                  ->on('agreements')
                  ->onDelete('cascade');

            $table->foreign('agreement_version_id')
                  ->references('id')
                  ->on('agreement_versions')
                  ->onDelete('cascade');

            $table->unique(['user_id', 'agreement_id', 'agreement_version_id']);

            $table->index(['agreement_id', 'agreement_version_id']);
            $table->index(['user_id', 'accepted_at']);
            $table->index(['accepted_at']); // İmza tarihi bazlı sorgular için
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreement_user');
        Schema::dropIfExists('agreement_versions');
        Schema::dropIfExists('agreements');
    }
};
