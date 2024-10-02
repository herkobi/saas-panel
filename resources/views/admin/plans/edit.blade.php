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
                    <form action="{{ route('panel.settings.page.update', $plan->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-md-3 col-form-label required">Plan Adı</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $plan->title) }}" placeholder="Plan Adı" required>
                                    <small class="form-hint">Lütfen plan adını giriniz.</small>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-md-3 col-form-label required">Plan Açıklaması</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" name="desc"
                                        class="form-control @error('desc') is-invalid @enderror"
                                        value="{{ old('desc', $plan->desc) }}" placeholder="Plan Açıklaması" required>
                                    <small class="form-hint">Lütfen plan ile ilgili kısa açıklama giriniz.</small>
                                    @error('desc')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row align-items-start">
                                <label class="col-lg-2 col-md-3 col-form-label required">Plan Yapısı</label>
                                <div class="col-lg-10 col-md-9">
                                    <div class="row mb-3 mt-2">
                                        <div class="col-lg-12">
                                            <div class="custom-control custom-switch mw-160">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input mt-0" type="checkbox" role="switch"
                                                        id="free" name="free" value="1"
                                                        {{ $plan->free ? 'checked' : '' }}>
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
                                                @foreach (['Year' => 'Yıl', 'Month' => 'Ay', 'Week' => 'Hafta', 'Day' => 'Gün'] as $value => $label)
                                                    <option value="PeriodicityType::{{ $value }}"
                                                        {{ old('periodicity_type', $plan->periodicity_type) == "PeriodicityType::$value" ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="col-form-label required">Döngü Zamanı</label>
                                            <input type="text" id="periodicity" name="periodicity" class="form-control"
                                                value="{{ old('periodicity', $plan->periodicity) }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label class="col-form-label required">Plan Ücreti</label>
                                            <input type="text" id="price" name="price" class="form-control"
                                                value="{{ old('price', $plan->price) }}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="col-form-label required">Deneme Süresi</label>
                                            <input type="text" id="grace_days" name="grace_days"
                                                class="form-control @error('grace_days') is-invalid @enderror"
                                                value="{{ old('grace_days', $plan->grace_days) }}"
                                                placeholder="Deneme Süresi" required>
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
                                                        value="{{ $feature->id }}" id="feature_{{ $feature->id }}"
                                                        name="features[{{ $feature->id }}][selected]"
                                                        {{ $plan->features->contains($feature->id) ? 'checked' : '' }}>
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
                                                    <label class="col-form-label pt-0">Kullanım Miktarı</label>
                                                    <input type="text" name="features[{{ $feature->id }}][limit]"
                                                        class="form-control form-control-sm rounded-0"
                                                        value="{{ old("features.{$feature->id}.limit", $plan->features->find($feature->id)->pivot->limit ?? '') }}">
                                                </div>
                                            @endif
                                            @if ($feature->quota == true)
                                                <div class="col-lg-4">
                                                    <label class="col-form-label pt-0">Dosya Kotası</label>
                                                    <input type="text" name="features[{{ $feature->id }}][quota]"
                                                        class="form-control form-control-sm rounded-0"
                                                        value="{{ old("features.{$feature->id}.quota", $plan->features->find($feature->id)->pivot->quota ?? '') }}">
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Güncelle</button>
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
            const freeSwitch = document.getElementById('free');
            const fieldsToDisable = ['periodicity_type', 'periodicity', 'price', 'grace_days'];

            function toggleFields() {
                const isDisabled = freeSwitch.checked;
                fieldsToDisable.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.disabled = isDisabled;
                    }
                });
            }

            freeSwitch.addEventListener('change', toggleFields);
            toggleFields(); // Initial state

            const featureCheckboxes = document.querySelectorAll('input[type="checkbox"][id^="feature_"]');

            featureCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const featureId = this.id.split('_')[1];
                    const limitInput = this.closest('.row').querySelector('input[name^="features[' +
                        featureId + '][limit]"]');
                    const quotaInput = this.closest('.row').querySelector('input[name^="features[' +
                        featureId + '][quota]"]');

                    if (limitInput) {
                        limitInput.disabled = !this.checked;
                    }
                    if (quotaInput) {
                        quotaInput.disabled = !this.checked;
                    }
                });

                // Set initial state
                checkbox.dispatchEvent(new Event('change'));
            });
        });
    </script>
@endsection
