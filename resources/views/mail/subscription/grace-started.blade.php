@component('mail::message')
    # Sayın {{ $user->name }},

    {{ $tenant->code }} kodlu hesabınıza ait {{ $plan->name }} paket aboneliğinizin süresi dolmak üzeredir. Ek süre (grace
    period) başlamıştır.

    Aboneliğinizin kesintiye uğramaması için {{ $grace_days_ended_at->format('d.m.Y H:i') }} tarihine kadar ödeme yapmanız
    gerekmektedir.

    @component('mail::button', ['url' => route('app.account.payment.create', $plan->id)])
        Ödeme Yap
    @endcomponent

    Saygılarımızla,<br>
    {{ Setting::get('title') }}
@endcomponent
