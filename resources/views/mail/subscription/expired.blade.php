@component('mail::message')
    # Sayın {{ $user->name }},

    {{ $tenant->code }} kodlu hesabınıza ait {{ $plan->name }} paket aboneliğinizin süresi sona ermiştir.

    @if ($has_grace)
        Hesabınız {{ $subscription->grace_days_ended_at->format('d.m.Y H:i') }} tarihine kadar kısıtlı erişime açık
        olacaktır.
    @else
        Hesabınız askıya alınmıştır.
    @endif

    Hizmeti kullanmaya devam edebilmek için ödeme yapmanız gerekmektedir.

    @component('mail::button', ['url' => route('app.account.payment.create', $plan->id)])
        Ödeme Yap
    @endcomponent

    Saygılarımızla,<br>
    {{ Setting::get('title') }}
@endcomponent
