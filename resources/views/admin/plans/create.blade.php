@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Planlar',
    ])
    <div class="page-content flex-grow-1 d-flex flex-column shadow-sm">
        <div class="row flex-grow-1">
            <div class="col-20 col-lg-3 col-md-3">
                <div class="page-menu rounded-2">
                    @include('admin.plans.include.navigation')
                </div>
            </div>
            <div class="col-80 col-lg-9 col-md-9">
                <div class="card h-100 border-0 mb-5">
                    <div class="card-header border-0 bg-white p-0 mb-3">
                        <div class="d-flex align-items-center justify-content-between w-100 border-bottom pb-2">
                            <h1 class="card-title">Yeni Plan Ekle</h1>
                        </div>
                    </div>
                    <form action="{{ route('panel.plan.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-md-3 col-form-label required">Plan Adı</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        placeholder="Plan Adı" required>
                                    <small class="form-hint">Lütfen plan adını giriniz.</small>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-md-3 col-form-label required">Plan Açıklaması</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" name="desc"
                                        class="form-control @error('desc') is-invalid @enderror" value="{{ old('desc') }}"
                                        placeholder="Plan Açıklaması" required>
                                    <small class="form-hint">Lütfen plan ile ilgili kısa açıklama giriniz.</small>
                                    @error('desc')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row align-items-start">
                                <label class="col-lg-2 col-md-3 col-form-label required">Plan Yapısı</label>
                                <div class="col-lg-10 col-md-9">
                                    <div class="plan-features mb-5">
                                        <div class="row mb-3 mt-2">
                                            <div class="col-lg-12">
                                                <div class="custom-control custom-switch mw-160">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input mt-0" type="checkbox" role="switch"
                                                            id="free" name="free" value="1">
                                                        <label class="form-check-label" for="free">Ücretsiz Plan</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <label class="col-form-label required">Plan Döngüsü</label>
                                                <select class="form-select" id="periodicity_type" name="periodicity_type">
                                                    <option>Seçiniz...</option>
                                                    <option value="PeriodicityType::Year">Yıl</option>
                                                    <option value="PeriodicityType::Month">Ay</option>
                                                    <option value="PeriodicityType::Week">Hafta</option>
                                                    <option value="PeriodicityType::Day">Gün</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="col-form-label required">Döngü Zamanı</label>
                                                <input type="text" id="periodicity" name="periodicity"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <label class="col-form-label required">Plan Ücreti</label>
                                                <input type="text" id="price" name="price" class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="col-form-label required">Deneme Süresi</label>
                                                <input type="text" id="grace_days" name="grace_days"
                                                    class="form-control @error('code') is-invalid @enderror"
                                                    value="{{ old('grace_days') }}" placeholder="Deneme Süresi" required>
                                                <small class="form-hint">Ödeme yapılmadan önce deneme süresi olacaksa ilgili
                                                    süreyi giriniz. Girdiğiniz değer gün olarak değerlendirilir.</small>
                                                @error('grace_days')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="h4 border-bottom pb-3 mb-3">Özellikler</div>
                                        @foreach ($features as $feature)
                                            <div class="row mb-3 align-items-start">
                                                <div class="col-lg-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="feature[{{ $feature->id }}]" value="{{ $feature->id }}"
                                                            id="feature_{{ $feature->id }}">
                                                        <label class="form-check-label" for="feature_{{ $feature->id }}">
                                                            <span class="fw-medium">{{ $feature->name }}</span>
                                                            <ul>
                                                                @if ($feature->consumable == true)
                                                                    <li>Kullanımı Takip Ediliyor</li>
                                                                @endif
                                                                @if ($feature->quota == true)
                                                                    <li>Dosya Kotası Var</li>
                                                                @endif
                                                                @if ($feature->postpaid == true)
                                                                    <li>Sonradan Ödemeli</li>
                                                                @endif
                                                            </ul>
                                                        </label>
                                                    </div>
                                                </div>
                                                @if ($feature->consumable == true)
                                                    <div class="col-lg-4">
                                                        @if ($feature->quota == true)
                                                            <label class="col-form-label pt-0">Dosya Alanı</label>
                                                        @else
                                                            <label class="col-form-label pt-0">Kullanım Miktarı</label>
                                                        @endif
                                                        <input type="text" name="feature[{{ $feature->id }}][limit]"
                                                            id="limit_{{ $feature->id }}"
                                                            class="form-control form-control-sm rounded-0 limit" disabled>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-submit mb-3">
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M7 12l5 5l10 -10"></path>
                                                <path d="M2 12l5 5m5 -5l5 -5"></path>
                                            </svg>
                                            Plan Ekle
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Free plan switch kontrolü
            const freeSwitch = document.getElementById('free');
            const fieldsToDisable = ['periodicity_type', 'periodicity', 'price', 'grace_days'];

            freeSwitch.addEventListener('change', function() {
                fieldsToDisable.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.disabled = this.checked;
                    }
                });
            });

            // Feature checkbox kontrolü
            const featureCheckboxes = document.querySelectorAll('input[type="checkbox"][id^="feature_"]');

            featureCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const featureId = this.id.split('_')[1];
                    const limitInput = this.closest('.row').querySelector('.limit');

                    if (limitInput) {
                        limitInput.disabled = !this.checked;
                    }
                    if (quotaInput) {
                        quotaInput.disabled = !this.checked;
                    }
                });
            });
        });
    </script>
@endsection
