<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Services\Tenant\SubscriptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RenewSubscriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:renew {--days=0 : Days threshold for renewals}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew subscriptions that are about to expire or have expired';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionService $subscriptionService)
    {
        $daysThreshold = $this->option('days');

        $this->info("Looking for subscriptions to renew (threshold: {$daysThreshold} days)...");

        // Bugün veya belirtilen gün sayısı içinde sona erecek yenilenebilir abonelikler
        $renewableSubscriptions = Subscription::query()
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->where('is_recurring', true)
            ->whereNull('cancelled_at')
            ->whereNull('cancellation_scheduled_at')
            ->whereNotNull('ends_at')
            ->whereDate('ends_at', '<=', now()->addDays($daysThreshold))
            ->get();

        $count = $renewableSubscriptions->count();
        $this->info("Found {$count} subscription(s) to renew.");

        foreach ($renewableSubscriptions as $subscription) {
            $this->renewSubscription($subscription, $subscriptionService);
        }

        $this->info('Done!');

        return Command::SUCCESS;
    }

    /**
     * Renew a specific subscription.
     */
    protected function renewSubscription(Subscription $subscription, SubscriptionService $subscriptionService): void
    {
        $this->info("Processing renewal for subscription #{$subscription->id}");

        try {
            // Aboneliği yenile
            $newSubscription = $subscriptionService->renewSubscription($subscription);

            $this->info("Subscription renewed successfully. New subscription ID: {$newSubscription->id}");
        } catch (\Throwable $e) {
            $this->error("Failed to renew subscription: {$e->getMessage()}");
            Log::error('Failed to renew subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
