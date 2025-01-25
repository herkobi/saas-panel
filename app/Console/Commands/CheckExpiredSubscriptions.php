<?php

namespace App\Console\Commands;

use App\Models\{Activity, Order};
use App\Notifications\User\Subscription\{Expired, GraceEnded, GraceStarted};
use App\Services\{LoggingService, OrderService};
use App\Services\Admin\Tools\OrderstatusService;
use App\Traits\{AuthUser, LogActivity};
use Illuminate\Console\Command;
use LucasDotVin\Soulbscription\Models\Subscription;

class CheckExpiredSubscriptions extends Command
{
   use AuthUser, LogActivity;

   protected $signature = 'app:check-expired-subscriptions';
   protected $description = 'Check expired subscriptions and handle notifications/renewal orders';

   protected $loggingService;
   protected $activity;
   protected $orderService;
   protected $orderStatusService;

   public function __construct(
       LoggingService $loggingService,
       Activity $activity,
       OrderService $orderService,
       OrderstatusService $orderStatusService
   ) {
       parent::__construct();
       $this->loggingService = $loggingService;
       $this->activity = $activity;
       $this->orderService = $orderService;
       $this->orderStatusService = $orderStatusService;
       $this->initializeAuthUser();
   }

   public function handle()
   {
       $this->checkRenewalNeeded();    // 7 gün kala yenileme kontrolü
       $this->checkGraceStarting();    // Grace başlangıç bildirimi
       $this->checkGraceSubscriptions(); // Grace bitiş bildirimi
       $this->checkNoGraceSubscriptions(); // Normal süre bitiş bildirimi
   }

   private function checkRenewalNeeded()
   {
       // Ücretli ve bitiş tarihi olan abonelikler
       $subscriptions = Subscription::query()
           ->whereHas('plan', fn($q) => $q->where('price', '>', 0))
           ->whereNotNull('expired_at')
           ->where('expired_at', '<=', now()->addDays(7))
           ->get();

       foreach($subscriptions as $subscription) {
           $tenant = $subscription->subscriber;
           $tenantOwner = $tenant->users()->where('is_tenant_owner', true)->first();

           // Bekleyen ödeme var mı kontrol et
           $hasPendingOrder = Order::where('tenant_id', $tenant->id)
               ->where('plan_id', $subscription->plan_id)
               ->whereHas('orderstatus', fn($q) => $q->where('code', 'PENDING_PAYMENT'))
               ->exists();

           if (!$hasPendingOrder) {
                // Ödeme kaydı oluştur
                $orderData = [
                    'user_id' => $tenantOwner->id,
                    'tenant_id' => $tenant->id,
                    'plan_id' => $subscription->plan_id,
                    'currency_id' => $subscription->plan->currency_id,
                    'amount' => $subscription->plan->price,
                    'payment_type' => 'bank',
                    'country_id' => $tenant->account->country_id,
                    'state_id' => $tenant->account->state_id,
                    'invoice_data' => [
                        'invoice_name' => $tenant->account->invoice_name,
                        'tax_number' => $tenant->account->tax_number,
                        'tax_office' => $tenant->account->tax_office,
                        'address' => $tenant->account->address,
                        'zip_code' => $tenant->account->zip_code,
                        'country_id' => $tenant->account->country_id,
                        'state_id' => $tenant->account->state_id,
                    ],
                    'notes' => 'Otomatik oluşturulan yenileme ödemesi',
                    'orderstatus_id' => $this->orderStatusService->getOrderstatusByCode('PENDING_PAYMENT')->id,
                ];

                $this->orderService->createPaymentOrder($orderData);

               // Loglama
               $this->loggingService->logUserAction(
                   'subscription.renewal.order.created',
                   $tenantOwner,
                   $subscription,
                   ['plan_id' => $subscription->plan_id]
               );

               Activity::create([
                   'message' => 'subscription.renewal.order.created',
                   'log' => $this->logActivity(
                       'renewal order created',
                       $tenantOwner,
                       $subscription->plan_id,
                       ['plan_id' => $subscription->plan_id]
                   ),
               ]);
           }
       }
   }

   private function checkGraceSubscriptions()
   {
       $expiredGraceSubscriptions = Subscription::query()
           ->whereNotNull('grace_days_ended_at')
           ->where('grace_days_ended_at', '<=', now())
           ->get();

       foreach($expiredGraceSubscriptions as $subscription) {
           $tenant = $subscription->subscriber;
           $tenantOwner = $tenant->users()->where('is_tenant_owner', true)->first();

           // Grace period sonu bildirimi
           $tenantOwner->notify(new GraceEnded($subscription, $tenant));

           $this->loggingService->logUserAction(
               'subscription.expired.with.grace',
               $tenantOwner,
               $subscription,
               [
                   'plan_id' => $subscription->plan_id,
                   'expired_at' => $subscription->expired_at,
                   'grace_days_ended_at' => $subscription->grace_days_ended_at
               ]
           );

           Activity::create([
               'message' => 'subscription.expired.with.grace',
               'log' => $this->logActivity(
                   'subscription expired with grace',
                   $tenantOwner,
                   $subscription->plan_id,
                   [
                       'plan_id' => $subscription->plan_id,
                       'expired_at' => $subscription->expired_at,
                       'grace_days_ended_at' => $subscription->grace_days_ended_at
                   ]
               ),
           ]);
       }
   }

   private function checkNoGraceSubscriptions()
   {
       $expiredNoGraceSubscriptions = Subscription::query()
           ->whereNull('grace_days_ended_at')
           ->where('expired_at', '<=', now())
           ->get();

       foreach($expiredNoGraceSubscriptions as $subscription) {
           $tenant = $subscription->subscriber;
           $tenantOwner = $tenant->users()->where('is_tenant_owner', true)->first();

           // Normal süre bitiş bildirimi
           $tenantOwner->notify(new Expired($subscription, $tenant));

           $this->loggingService->logUserAction(
               'subscription.expired',
               $tenantOwner,
               $subscription,
               [
                   'plan_id' => $subscription->plan_id,
                   'expired_at' => $subscription->expired_at
               ]
           );

           Activity::create([
               'message' => 'subscription.expired',
               'log' => $this->logActivity(
                   'subscription expired',
                   $tenantOwner,
                   $subscription->plan_id,
                   [
                       'plan_id' => $subscription->plan_id,
                       'expired_at' => $subscription->expired_at
                   ]
               ),
           ]);
       }
   }

   private function checkGraceStarting()
   {
       $startingGraceSubscriptions = Subscription::query()
           ->whereNotNull('grace_days_ended_at')
           ->where('expired_at', '<=', now())
           ->where('grace_days_ended_at', '>', now())
           ->get();

       foreach($startingGraceSubscriptions as $subscription) {
           $tenant = $subscription->subscriber;
           $tenantOwner = $tenant->users()->where('is_tenant_owner', true)->first();

           // Grace period başlangıç bildirimi
           $tenantOwner->notify(new GraceStarted($subscription, $tenant));

           $this->loggingService->logUserAction(
               'subscription.grace.started',
               $tenantOwner,
               $subscription,
               [
                   'plan_id' => $subscription->plan_id,
                   'expired_at' => $subscription->expired_at,
                   'grace_days_ended_at' => $subscription->grace_days_ended_at
               ]
           );

           Activity::create([
               'message' => 'subscription.grace.started',
               'log' => $this->logActivity(
                   'subscription grace period started',
                   $tenantOwner,
                   $subscription->plan_id,
                   [
                       'plan_id' => $subscription->plan_id,
                       'expired_at' => $subscription->expired_at,
                       'grace_days_ended_at' => $subscription->grace_days_ended_at
                   ]
               ),
           ]);
       }
   }
}
