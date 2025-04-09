<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Services\Tenant\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiringSubscriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check-expiring {--days=3 : Days before expiration to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for subscriptions that are about to expire and send notifications';

    /**
     * @var NotificationService
     */
    protected $notificationService;

    /**
     * Constructor
     */
    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysBeforeExpiration = $this->option('days');

        $this->info("Checking subscriptions expiring in {$daysBeforeExpiration} days...");

        // Süresi belirtilen gün sayısı içinde dolacak olan aktif abonelikler
        $expiringSubscriptions = Subscription::query()
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->whereNull('cancelled_at')
            ->whereNotNull('ends_at')
            ->whereBetween('ends_at', [
                now(),
                now()->addDays($daysBeforeExpiration)
            ])
            ->get();

        $count = $expiringSubscriptions->count();
        $this->info("Found {$count} subscription(s) expiring soon.");

        foreach ($expiringSubscriptions as $subscription) {
            $this->processExpiringSubscription($subscription);
        }

        $this->info('Done!');

        return Command::SUCCESS;
    }

    /**
     * Process a single expiring subscription.
     */
    protected function processExpiringSubscription(Subscription $subscription): void
    {
        $tenant = $subscription->tenant;

        if (!$tenant) {
            $this->warn("Subscription #{$subscription->id} has no tenant.");
            return;
        }

        // Tenant sahibini bul
        $owner = $tenant->users()->whereType('tenant_owner')->first();

        if (!$owner) {
            $this->warn("Tenant #{$tenant->id} has no owner.");
            return;
        }

        $daysLeft = now()->diffInDays($subscription->ends_at);

        $this->info("Notifying tenant owner of subscription #{$subscription->id} expiring in {$daysLeft} days.");

        try {
            // Bildirim gönder - NotificationService kullanıyoruz
            $this->notificationService->notifySubscriptionExpiring($subscription, $owner, $daysLeft);

            $this->info("Notification sent to {$owner->email}");
        } catch (\Throwable $e) {
            $this->error("Failed to send notification: {$e->getMessage()}");
            Log::error('Failed to send subscription expiring notification', [
                'subscription_id' => $subscription->id,
                'tenant_id' => $tenant->id,
                'user_id' => $owner->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
