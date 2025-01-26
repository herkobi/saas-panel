<?php

namespace App\Http\Controllers\User\Account\Payments;

use App\Actions\User\Order\Create;
use App\Actions\User\Order\Upload;
use App\Actions\User\Account\Update as UpdateAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Payment\PaymentCreateRequest;
use App\Http\Requests\User\Payment\PaymentUploadRequest;
use App\Services\OrderService;
use App\Services\Admin\Tools\{
   CountryService,
   CurrencyService,
   TaxService,
   OrderStatusService
};
use App\Services\Admin\Settings\{
    AgreementService,
    BacsService
};
use App\Facades\Setting;
use App\Models\Plan;
use App\Services\Admin\Plan\PlanService;
use App\Traits\AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PaymentsController extends Controller
{
   use AuthUser;

   protected $planService;
   protected $orderService;
   protected $orderStatusService;
   protected $currencyService;
   protected $countryService;
   protected $bacsService;
   protected $agreementService;
   protected $taxService;
   protected $createPayment;
   protected $uploadDocument;
   protected $updateAccount;

   public function __construct(
       PlanService $planService,
       OrderService $orderService,
       OrderStatusService $orderStatusService,
       CurrencyService $currencyService,
       CountryService $countryService,
       BacsService $bacsService,
       AgreementService $agreementService,
       TaxService $taxService,
       Create $createPayment,
       Upload $uploadDocument,
       UpdateAccount $updateAccount
   ) {
       $this->initializeAuthUser();
       $this->planService = $planService;
       $this->orderService = $orderService;
       $this->orderStatusService = $orderStatusService;
       $this->currencyService = $currencyService;
       $this->countryService = $countryService;
       $this->bacsService = $bacsService;
       $this->agreementService = $agreementService;
       $this->taxService = $taxService;
       $this->createPayment = $createPayment;
       $this->uploadDocument = $uploadDocument;
       $this->updateAccount = $updateAccount;
   }

   public function index(): View
   {
       $orders = $this->orderService->getUserOrders($this->user->id);
       return view('user.account.payments.index', [
           'orders' => $orders
       ]);
   }

   public function create(Plan $plan): View|RedirectResponse
   {
        // Önce tenant owner kontrolü
        if (!$this->user->is_tenant_owner) {
            return redirect()
                ->back()
                ->with('error', 'Ödeme işlemi yapma yetkiniz bulunmuyor.');
        }

        // Eğer tenant owner ise bekleyen ödeme kontrolü
        if ($this->orderService->hasUncompletedOrders($this->user->tenant_id)) {
            return redirect()
                ->route('app.account.payments')
                ->with('error', 'Bekleyen bir ödeme işleminiz bulunmaktadır. Lütfen önce mevcut ödeme işlemini tamamlayın veya bekleyin.');
        }

        // Gerekli verileri al
        $currencies = $this->currencyService->getActiveCurrencies();
        $countries = $this->countryService->getActiveCountries();
        $bacs = $this->bacsService->getActiveBacs();
        $agreements = $this->agreementService->getPaymentAgreements();
        $taxes = $this->taxService->getActiveTaxes();
        $defaultCountry = $this->countryService->getCountryByCode(Setting::get('location'));
        return view('user.account.payments.create', [
            'plan' => $plan,
            'currencies' => $currencies,
            'countries' => $countries,
            'bacs' => $bacs,
            'agreements' => $agreements,
            'taxes' => $taxes,
            'defaultCountry' => $defaultCountry,
            'user' => $this->user
        ]);
   }

   public function store(PaymentCreateRequest $request): RedirectResponse
   {
        // Fatura bilgilerini güncelle
        $accountData = array_intersect_key($request->all(), array_flip([
            'invoice_name', 'tax_number', 'tax_office',
            'address', 'zip_code', 'country_id', 'state_id'
        ]));

        $accountUpdated = $this->updateAccount->execute($this->user->account->id, $accountData);

        if (!$accountUpdated) {
            return Redirect::back()->with('error', 'Fatura bilgileri güncellenirken bir hata oluştu.');
        }

        $plan = $this->planService->getPlanById($request->plan_id);

        // Ödeme yöntemine göre durum belirle
        $status = $plan->price == 0
            ? $this->orderStatusService->getOrderstatusByCode('APPROVED')
            : match($request->payment_method) {
                'bank' => $this->orderStatusService->getOrderstatusByCode('PENDING_PAYMENT'),
                'credit_card' => $this->orderStatusService->getOrderstatusByCode('REVIEW')
            };

        $agreements = collect($request->agreements)
            ->filter(fn($value) => in_array($value, [true, '1', 1]))
            ->keys()
            ->toArray();

        $orderData = array_merge($accountData, [
            'user_id' => $this->user->id,
            'tenant_id' => $this->user->tenant_id,
            'plan_id' => $plan->id,
            'currency_id' => $plan->currency_id,
            'amount' => $plan->price,
            'payment_type' => $plan->price == 0 ? 'free' : $request->payment_method,
            'notes' => $request->notes,
            'agreements' => $agreements,
            'orderstatus_id' => $status->id,
            'payment_date' => $plan->price == 0 ? now() : null  // Bunu ekleyelim
        ]);

        $created = $this->createPayment->execute($orderData);

        if (!$created) {
           return Redirect::back()->with('error', 'Ödeme kaydı oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
        }

        if ($plan->price == 0) {
            return Redirect::route('app.account.payment.free-success', $created->code);
        }

        return match($request->payment_method) {
                'bank' => Redirect::route('app.account.payment.bacs-success', $created->code)
            ->with('success', 'Ödeme kaydınız oluşturuldu. Lütfen havale/EFT bilgilerini kontrol ediniz.'),
                'credit_card' => Redirect::route('app.account.payment.process', $created->code)
            ->with('info', 'Ödeme sayfasına yönlendiriliyorsunuz...')
        };
   }

   public function show(string $code): View|RedirectResponse
   {
       $order = $this->orderService->getOrderByCode($code);

       if ($order->tenant_id !== $this->user->tenant_id) {
           return Redirect::back()->with('error', 'Bu ödeme kaydını görüntüleme yetkiniz yok.');
       }

       return view('user.account.payments.show', [
           'order' => $order
       ]);
   }

    public function freeSuccess(string $code): View|RedirectResponse
    {
        $order = $this->orderService->getOrderByCode($code);
        return view('user.account.payments.free-success', [
            'order' => $order
        ]);
    }

    public function bacsSuccess(string $code): View|RedirectResponse
    {
        $order = $this->orderService->getOrderByCode($code);

        // Yetki kontrolü
        if ($order->user_id !== $this->user->id) {
            return Redirect::back()->with('error', 'Bu ödeme kaydını görüntüleme yetkiniz yok.');
        }

        // EFT/Havale ödemesi değilse yönlendir
        if ($order->payment_type !== 'bank') {
            return Redirect::route('app.account.payments')
                ->with('error', 'Geçersiz ödeme yöntemi.');
        }

        $bacs = $this->bacsService->getActiveBacs();

        return view('user.account.payments.bacs-success', [
            'order' => $order,
            'bacs' => $bacs
        ]);
    }

    public function uploadDocument(PaymentUploadRequest $request, string $code): RedirectResponse
    {
        $order = $this->orderService->getOrderByCode($code);

        if ($order->tenant_id !== $this->user->tenant_id) {
            return Redirect::back()->with('error', 'Bu ödeme kaydını güncelleme yetkiniz yok.');
        }

        $uploaded = $this->uploadDocument->execute($order->id, $request->file('document'));

        if (!$uploaded) {
            return Redirect::back()->with('error', 'Dekont yüklenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
        }

        return Redirect::route('app.account.payment.show', $code)
            ->with('success', 'Dekont başarıyla yüklendi. İncelemeye alındı.');
    }

    public function normaluser(): View
    {
        return view('user.account.payments.user');
    }
}
