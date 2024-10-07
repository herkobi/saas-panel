@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Planı Düzenle',
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
                            <h1 class="card-title">Planı Düzenle: {{ $plan->name }}</h1>
                        </div>
                    </div>
                    <form action="{{ route('panel.plan.update', $plan->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-md-3 col-form-label required">Plan Adı</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $plan->name) }}" placeholder="Plan Adı" required>
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
                                        class="form-control @error('desc') is-invalid @enderror"
                                        value="{{ old('desc', $plan->description) }}" placeholder="Plan Açıklaması"
                                        required>
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
                                                            id="free" name="free" value="1"
                                                            {{ $plan->periodicity_type === null ? 'checked' : '' }}>
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
                                                    <option value="PeriodicityType::Year"
                                                        {{ $plan->periodicity_type === 'PeriodicityType::Year' ? 'selected' : '' }}>
                                                        Yıl</option>
                                                    <option value="PeriodicityType::Month"
                                                        {{ $plan->periodicity_type === 'PeriodicityType::Month' ? 'selected' : '' }}>
                                                        Ay</option>
                                                    <option value="PeriodicityType::Week"
                                                        {{ $plan->periodicity_type === 'PeriodicityType::Week' ? 'selected' : '' }}>
                                                        Hafta</option>
                                                    <option value="PeriodicityType::Day"
                                                        {{ $plan->periodicity_type === 'PeriodicityType::Day' ? 'selected' : '' }}>
                                                        Gün</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="col-form-label required">Döngü Zamanı</label>
                                                <input type="text" id="periodicity" name="periodicity"
                                                    class="form-control"
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
                                                            name="feature[{{ $feature->id }}]"
                                                            value="{{ $feature->id }}" id="feature_{{ $feature->id }}"
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
                                                        @if ($feature->quota == true)
                                                            <label class="col-form-label pt-0">Dosya Alanı</label>
                                                        @else
                                                            <label class="col-form-label pt-0">Kullanım Miktarı</label>
                                                        @endif
                                                        <input type="text" name="feature[{{ $feature->id }}][limit]"
                                                            id="limit_{{ $feature->id }}"
                                                            class="form-control form-control-sm rounded-0 limit"
                                                            value="{{ old("feature.{$feature->id}.limit", $plan->features->find($feature->id)->pivot->charges ?? '') }}"
                                                            {{ $plan->features->contains($feature->id) ? '' : 'disabled' }}>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <button type="submit" class="btn btn-success btn-sm w-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 12l5 5l10 -10" />
                                                    <path d="M2 12l5 5m5 -5l5 -5" />
                                                </svg>
                                                Özelliği Güncelle
                                            </button>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="float-end">
                                                <button type="button" class="btn btn-sm btn-outline-danger px-3"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 7l16 0" />
                                                        <path d="M10 11l0 6" />
                                                        <path d="M14 11l0 6" />
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('panel.plan.delete', $plan->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">Dikkat!</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <strong>{{ $plan->name }}</strong> isimli planı silmek üzeresiniz. Bu işlem geri alınamaz.
                        Emin misiniz?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">İptal
                            Et</button>
                        <button type="submit" class="btn btn-sm btn-danger">Evet, Planı Sil</button>
                    </div>
                </form>
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

            function toggleFields() {
                const isChecked = freeSwitch.checked;
                fieldsToDisable.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.disabled = isChecked;
                    }
                });
            }

            freeSwitch.addEventListener('change', toggleFields);
            toggleFields(); // Sayfa yüklendiğinde mevcut durumu ayarla

            // Feature checkbox kontrolü
            const featureCheckboxes = document.querySelectorAll('input[type="checkbox"][id^="feature_"]');

            featureCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const featureId = this.id.split('_')[1];
                    const limitInput = document.getElementById(`limit_${featureId}`);

                    if (limitInput) {
                        limitInput.disabled = !this.checked;
                    }
                });
            });
        });
    </script>
@endsection
