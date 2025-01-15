@extends('layouts.app')
@section('content')
    @include('user.include.header', [
        'title' => 'Hesabım',
    ])
    @include('user.account.include.navigation')
    <div class="page-content mt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account.include.sidebar')
                </div>
                <div class="col-lg-9">
                    @if ($subscription)
                        <!-- Aktif Plan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Aktif Plan</h5>
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <h4>{{ $subscription->plan->name }}</h4>
                                                <h5 class="text-primary">{{ $subscription->plan->formatted_price }}</h5>
                                                <p class="text-muted small mb-0">
                                                    {{ $subscription->plan->periodicity }}
                                                    {{ $subscription->plan->periodicity_type == 'PeriodicityType::Day'
                                                        ? 'Gün'
                                                        : ($subscription->plan->periodicity_type == 'PeriodicityType::Week'
                                                            ? 'Hafta'
                                                            : ($subscription->plan->periodicity_type == 'PeriodicityType::Month'
                                                                ? 'Ay'
                                                                : 'Yıl')) }}
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                @if ($pendingOrder)
                                                    <div class="alert alert-warning">
                                                        <h6 class="alert-heading">Ödeme Bekleniyor!</h6>
                                                        <p class="mb-2">Bu paketi kullanabilmek için ödeme yapmanız
                                                            gerekmektedir.</p>
                                                        <a href="{{ route('app.account.payment.create', $subscription->plan->id) }}"
                                                            class="btn btn-warning">
                                                            <i class="bi bi-credit-card me-2"></i>Ödeme Yap
                                                        </a>
                                                    </div>
                                                @endif

                                                @if ($subscription->plan->features->count() > 0)
                                                    <div class="row">
                                                        @foreach ($subscription->plan->features as $feature)
                                                            <div class="col-md-6">
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <i class="bi bi-check-circle text-success me-2"></i>
                                                                    <span>
                                                                        {{ $feature->name }}
                                                                        @if ($feature->pivot->charges)
                                                                            <small
                                                                                class="text-muted">({{ (int) $feature->pivot->charges }}
                                                                                Adet)</small>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Tenant'a Özel Planlar -->
                    @if ($tenantPlans->isNotEmpty())
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">Size Özel Planlar</h5>
                                <div class="row g-3">
                                    @foreach ($tenantPlans as $plan)
                                        <div class="col-md-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <h4 class="card-title">{{ $plan->name }}</h4>
                                                    @if ($plan->description)
                                                        <p class="card-text text-muted small">{{ $plan->description }}</p>
                                                    @endif
                                                    <h5 class="text-primary my-3">{{ $plan->formatted_price }}</h5>
                                                    @if ($plan->features->count() > 0)
                                                        <ul class="list-unstyled mb-4">
                                                            @foreach ($plan->features as $feature)
                                                                <li class="mb-2">
                                                                    <i class="bi bi-check-circle text-success"></i>
                                                                    {{ $feature->name }}
                                                                    @if ($feature->pivot->charges)
                                                                        <br>
                                                                        <small
                                                                            class="text-muted">({{ (int) $feature->pivot->charges }}
                                                                            Adet)</small>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                    <a href="{{ route('app.account.payment.create', ['plan' => $plan->id]) }}"
                                                        class="btn btn-primary">
                                                        {{ $subscription ? 'Geçiş Yap' : 'Satın Al' }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Genel Planlar -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">
                                {{ $tenantPlans->isNotEmpty() ? 'Diğer Planlar' : 'Kullanılabilir Planlar' }}</h5>
                            <div class="row g-3">
                                @foreach ($generalPlans as $plan)
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-body text-center">
                                                <h4 class="card-title">{{ $plan->name }}</h4>
                                                @if ($plan->description)
                                                    <p class="card-text text-muted small">{{ $plan->description }}</p>
                                                @endif
                                                <h5 class="text-primary my-3">{{ $plan->formatted_price }}</h5>
                                                @if ($plan->features->count() > 0)
                                                    <ul class="list-unstyled mb-4">
                                                        @foreach ($plan->features as $feature)
                                                            <li class="mb-2">
                                                                <i class="bi bi-check-circle text-success"></i>
                                                                {{ $feature->name }}
                                                                @if ($feature->pivot->charges)
                                                                    <br>
                                                                    <small
                                                                        class="text-muted">({{ (int) $feature->pivot->charges }}
                                                                        Adet)</small>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                                <a href="{{ route('app.account.payment.create', ['plan' => $plan->id]) }}"
                                                    class="btn btn-primary">
                                                    {{ $subscription ? 'Geçiş Yap' : 'Satın Al' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
