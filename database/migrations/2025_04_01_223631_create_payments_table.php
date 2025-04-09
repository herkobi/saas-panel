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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->string('payment_method');
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency_code', 3);
            $table->string('status');  // succeeded, pending, failed
            $table->datetime('paid_at')->nullable();

            // Fatura bilgileri
            $table->string('billing_name');
            $table->text('billing_address');
            $table->string('billing_city');
            $table->string('billing_district');
            $table->string('billing_postal_code');
            $table->string('billing_tax_office')->nullable();
            $table->string('billing_tax_number')->nullable();
            $table->string('billing_email');
            $table->string('billing_contact_name');
            $table->string('billing_phone');

            $table->json('payment_data')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
