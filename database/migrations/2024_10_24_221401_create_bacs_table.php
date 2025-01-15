<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bacs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('currency_id');
            $table->integer('status')->default(Status::ACTIVE->value);
            $table->string('logo')->nullable();
            $table->string('bank_name');
            $table->string('account_holder');
            $table->string('account_number')->nullable()->unique();
            $table->string('iban')->nullable()->unique();
            $table->string('swift')->nullable();
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');

            $table->index(['bank_name']);
            $table->index(['status', 'bank_name']);
            $table->index(['currency_id', 'bank_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bacs');
    }
};
