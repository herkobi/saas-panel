@component('mail::message')
    # Sayın {{ $user->name }},

    {{ $tenant->code }} kodlu hesabınıza ait {{ $plan->name }} paket aboneliğinizin ek süresi sona ermiştir. Hesabınız
    askıya alınmıştır.

    Hizmeti kullanmaya devam edebilmek için ödeme yapmanız gerekmektedir.

    @component('mail::button', ['url' => route('app.account.payment.create', $plan->id)])
        Ödeme Yap
    @endcomponent

    Saygılarımızla,<br>
    {{ Setting::get('title') }}
@endcomponent
