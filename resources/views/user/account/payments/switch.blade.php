@extends('layouts.app')
@section('content')
    @include('user.include.header', [
        'title' => 'Plan Değişikliği',
    ])
    @include('user.account.include.navigation')
    <div class="page-content mt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="row align-items-center pb-2">
                        <div class="col-lg-12">
                            <h3 class="form-title border-bottom mb-3 pb-3">Plan Değişikliği</h3>
                        </div>
                    </div>
                    <form action="{{ route('app.account.payment.switch.store') }}" method="post" class="mb-5">
                        @csrf
                        <div class="row mb-4">
                            <!-- Sol Kolon - Fatura Bilgileri -->
                            <div class="col-md-7">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <!-- Mevcut fatura formu buraya -->
                                    </div>
                                </div>
                            </div>

                            <!-- Sağ Kolon - Plan ve Ödeme Detayları -->
                            <div class="col-md-5">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3 px-2">Plan Değişikliği Detayları</h5>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th class="text-dark">Mevcut Plan:</th>
                                                    <td>{{ $currentPlan->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-dark">Yeni Plan:</th>
                                                    <td>{{ $plan->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-dark">Mevcut Plan Ücreti:</th>
                                                    <td>{{ $currentPlan->formatted_price }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-dark">Yeni Plan Ücreti:</th>
                                                    <td>{{ $plan->formatted_price }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-dark">Fark Ücreti:</th>
                                                    <td>{{ number_format(max(0, $plan->price - $currentPlan->price), 2) }}
                                                        {{ $plan->currency->code }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sözleşmeler kartı -->
                                <div class="card mb-3">
                                    <!-- Mevcut sözleşmeler bölümü buraya -->
                                </div>
                            </div>
                        </div>

                        <!-- Ödeme Yöntemi -->
                        <div class="row mb-4">
                            <!-- Mevcut ödeme yöntemi seçimi buraya -->
                        </div>

                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-credit-card-2-front me-1" viewBox="0 0 20 20">
                                        <path
                                            d="M14 3a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zM2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z" />
                                        <path
                                            d="M2 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5" />
                                    </svg>
                                    Ödeme Yap
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
