<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Tenant\SubscriptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledSubscriptionChangesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:process-scheduled-changes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled subscription changes and cancellations';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionService $subscriptionService)
    {
        $this->info('Processing scheduled subscription changes...');

        // Planlanmış plan değişiklikleri
        $this->processScheduledPlanChanges($subscriptionService);

        // Planlanmış iptaller
        $this->processScheduledCancellations($subscriptionService);

        $this->info('Done!');

        return Command::SUCCESS;
    }

    /**
     * Process scheduled plan changes.
     */
    protected function processScheduledPlanChanges(SubscriptionService $subscriptionService): void
    {
        $pendingChanges = Subscription::query()
            ->whereNotNull('scheduled_plan_id')
            ->whereNotNull('change_scheduled_at')
            ->where('change_scheduled_at', '<=', now())
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->get();

        $count = $pendingChanges->count();
        $this->info("Found {$count} pending plan change(s).");

        foreach ($pendingChanges as $subscription) {
            $this->info("Processing plan change for subscription #{$subscription->id}");

            $newPlan = Plan::find($subscription->scheduled_plan_id);

            if (!$newPlan) {
                $this->warn("Plan #{$subscription->scheduled_plan_id} not found. Skipping change.");
                continue;
            }

            try {
                $subscriptionService->changePlanImmediately($subscription, $newPlan);
                $this->info("Plan changed successfully to {$newPlan->name}");
            } catch (\Throwable $e) {
                $this->error("Failed to change plan: {$e->getMessage()}");
                Log::error('Failed to process scheduled plan change', [
                    'subscription_id' => $subscription->id,
                    'current_plan_id' => $subscription->plan_id,
                    'new_plan_id' => $subscription->scheduled_plan_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Process scheduled cancellations.
     */
    protected function processScheduledCancellations(SubscriptionService $subscriptionService): void
    {
        $pendingCancellations = Subscription::query()
            ->whereNotNull('cancellation_scheduled_at')
            ->where('cancellation_scheduled_at', '<=', now())
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->get();

        $count = $pendingCancellations->count();
        $this->info("Found {$count} pending cancellation(s).");

        foreach ($pendingCancellations as $subscription) {
            $this->info("Processing cancellation for subscription #{$subscription->id}");

            try {
                $subscriptionService->cancelSubscription($subscription);
                $this->info("Subscription cancelled successfully");
            } catch (\Throwable $e) {
                $this->error("Failed to cancel subscription: {$e->getMessage()}");
                Log::error('Failed to process scheduled cancellation', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
