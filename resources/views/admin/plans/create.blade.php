@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Planlar',
    ])
    @include('admin.plans.include.navigation')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.plans.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="page-form row">
                        <div class="col-lg-10 offset-lg-1">
                            <h3 class="form-title border-bottom mb-3 pb-2">Yeni Plan</h3>
                            <form action="{{ route('panel.plan.store') }}" method="POST">
                                @csrf
                                <div class="row align-self-start mb-3">
                                    <label class="col-lg-2 col-md-3 col-form-label">Plan Yapısı</label>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="row mb-3 mt-2">
                                            <div class="col-lg-6">
                                                <div class="custom-control custom-switch">
                                                    <div class="form-check form-switch border-bottom pb-3 mb-2">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="forTenant">
                                                        <label class="form-check-label" for="forTenant">Hesap Bazlı
                                                            Plan</label>
                                                    </div>
                                                    <span class="form-hint small">Bir hesap için özel plan oluşturacaksınız
                                                        seçiniz.</span>
                                                </div>
                                            </div>
                                            <div id="tenant_select_container" class="col-lg-6" style="display:none;">
                                                <select class="form-select" name="base" id="tenant_select">
                                                    <option value="">Hesap Seçiniz...</option>
                                                    @foreach ($tenants as $tenant)
                                                        <option value="{{ $tenant->id }}">{{ $tenant->code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="name" class="col-form-label col-lg-2 col-md-3">Plan Adı</label>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="input-group">
                                            <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <input id="name" type="text" name="name"
                                                class="form-control rounded-0 border-start-0"
                                                placeholder="Plan Adını Giriniz" value="{{ old('name') }}" required>
                                        </div>
                                        <span class="form-hint small">Plan adını giriniz</span>
                                        @error('name')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="desc" class="col-form-label col-lg-2 col-md-3">Plan Açıklaması</label>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="input-group">
                                            <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-blockquote-left" viewBox="0 0 16 16">
                                                    <path
                                                        d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm5 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm.79-5.373q.168-.117.444-.275L3.524 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562Q2 7.587 2 7.969q0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282q.036-.305.123-.498a1.4 1.4 0 0 1 .252-.37 2 2 0 0 1 .346-.298zm2.167 0q.17-.117.445-.275L5.692 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562q-.165.31-.164.692 0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282a1.8 1.8 0 0 1 .118-.492q.087-.194.257-.375a2 2 0 0 1 .346-.3z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <input id="desc" type="text" name="desc"
                                                class="form-control rounded-0 border-start-0"
                                                placeholder="Plan Açıklamasını Giriniz" value="{{ old('desc') }}">
                                        </div>
                                        <span class="form-hint small">Plan açıklamasını giriniz</span>
                                        @error('desc')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="name" class="col-form-label col-lg-2 col-md-3">Plan Bilgileri</label>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="plan-features">
                                            <div class="row mb-3 mt-2">
                                                <div class="col-lg-4">
                                                    <div class="custom-control custom-switch">
                                                        <div class="form-check form-switch border-bottom pb-3 mb-2">
                                                            <input class="form-check-input" type="checkbox" role="switch"
                                                                id="free" name="free" value="1">
                                                            <label class="form-check-label" for="free">Ücretsiz
                                                                Plan</label>
                                                        </div>
                                                        <span class="form-hint small">Plan ücretsiz olacaksa lütfen
                                                            seçiniz</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-lg-6">
                                                    <label for="periodicity_type" class="col-form-label">Plan
                                                        Döngüsü</label>
                                                    <select class="form-select" id="periodicity_type"
                                                        name="periodicity_type">
                                                        <option>Seçiniz...</option>
                                                        @foreach ($periodicityTypes as $value => $label)
                                                            <option value="{{ $value }}"
                                                                @if (isset($plan) && $plan->periodicity_type === $value) selected @endif>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="form-hint small">Plan döngüsünü seçiniz</span>
                                                    @error('periodicity_type')
                                                        <div class="invalid-feedback" role="alert">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6">
                                                    <label for="periodicity" class="col-form-label">Döngü
                                                        Zamanı</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd"
                                                                    d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z">
                                                                </path>
                                                                <path
                                                                    d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <input type="text" id="periodicity" name="periodicity"
                                                            class="form-control rounded-0 border-start-0"
                                                            placeholder="Döngü Zamanı" value="{{ old('periodicity') }}">
                                                    </div>
                                                    <span class="form-hint small">Plan dönemini giriniz</span>
                                                    @error('periodicity')
                                                        <div class="invalid-feedback" role="alert">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-lg-6">
                                                    <label for="price" class="col-form-label">Plan Ücreti</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-cash-stack" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4">
                                                                </path>
                                                                <path
                                                                    d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <input type="text" id="price" name="price"
                                                            class="form-control rounded-0 border-start-0 border-end-0"
                                                            placeholder="Plan Ücreti" value="{{ old('price') }}">
                                                        <div
                                                            class="input-group-text rounded-0 border-start-0 bg-white p-0">
                                                            <select class="form-select border-0" id="currency_id"
                                                                name="currency_id">
                                                                @foreach ($currencies as $currency)
                                                                    <option value="{{ $currency->id }}">
                                                                        ({{ $currency->symbol }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <span class="form-hint small">Plan ücretini giriniz</span>
                                                    @error('price')
                                                        <div class="invalid-feedback" role="alert">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="col-form-label">Deneme Süresi</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-calendar3" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z">
                                                                </path>
                                                                <path
                                                                    d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <input type="text" id="grace_days" name="grace_days"
                                                            class="form-control rounded-0 border-start-0"
                                                            placeholder="Deneme Süresi" value="{{ old('grace_days') }}">
                                                    </div>
                                                    <span class="form-hint small">Ödeme yapılmadan önce deneme süresi
                                                        olacaksa ilgili süreyi giriniz. Girdiğiniz değer gün olarak
                                                        değerlendirilir.</span>
                                                    @error('grace_days')
                                                        <div class="invalid-feedback" role="alert">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="h5 border-bottom pb-2 mb-3">Özellikler</div>
                                            @foreach ($features as $feature)
                                                <div class="row mb-3 align-items-start">
                                                    <div class="col-lg-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="feature[{{ $feature->id }}]"
                                                                value="{{ $feature->id }}"
                                                                id="feature_{{ $feature->id }}">
                                                            <label class="form-check-label"
                                                                for="feature_{{ $feature->id }}">
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
                                                            <input type="text"
                                                                name="feature[{{ $feature->id }}][limit]"
                                                                id="limit_{{ $feature->id }}"
                                                                class="form-control form-control-sm rounded-0 limit"
                                                                disabled>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-lg-10 col-mg-9 offset-lg-2 offset-md-3">
                                        <button type="submit" class="btn rounded-1 px-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="currentColor" class="bi bi-floppy" viewBox="0 0 20 20">
                                                <path d="M11 2H9v3h2z"></path>
                                                <path
                                                    d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z">
                                                </path>
                                            </svg>
                                            KAYDET
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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

            // Tenant checkbox değişimi
            const forTenantCheckbox = document.getElementById('forTenant');
            const tenantSelectContainer = document.getElementById('tenant_select_container');

            forTenantCheckbox.addEventListener('change', function() {
                tenantSelectContainer.style.display = this.checked ? 'block' : 'none';
                if (!this.checked) {
                    document.getElementById('tenant_select').value = ''; // seçimi temizle
                }
            });

            // Sayfa yüklendiğinde kontrol et
            tenantSelectContainer.style.display = forTenantCheckbox.checked ? 'block' : 'none';
        });
    </script>
@endsection
