<?php

use App\Enums\SubscriptionStatus;
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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->dateTime('trial_ends_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->dateTime('grace_ends_at')->nullable();
            $table->dateTime('next_billing_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->string('billing_period')->nullable(); // monthly, yearly
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->string('tax_rate_code')->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->boolean('is_recurring')->default(true);
            $table->integer('status')->default(SubscriptionStatus::ACTIVE->value);
            $table->foreignId('scheduled_plan_id')->nullable()->constrained('plans');
            $table->dateTime('change_scheduled_at')->nullable()->index();
            $table->dateTime('cancellation_scheduled_at')->nullable()->index();
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();

            // İndexler
            $table->index('tenant_id');
            $table->index('plan_id');
            $table->index('status');
            $table->index('ends_at');
            $table->index('trial_ends_at');
            $table->index('next_billing_at');

            $table->index(['tenant_id', 'status']);
            $table->index(['status', 'ends_at']);
            $table->index(['tenant_id', 'plan_id', 'status']);
            $table->index(['tenant_id', 'change_scheduled_at']);
            $table->index(['tenant_id', 'cancellation_scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
