@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Araçlar',
    ])
    @include('admin.tools.include.navigation')
    <div class="page-content">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-3">
                    @include('admin.tools.config.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="page-form">
                        <h3 class="form-title border-bottom mb-3 pb-3">Yeni Vergi Oranı</h3>
                        <form action="{{ route('panel.tools.config.tax.store') }}" method="POST">
                            @csrf
                            <div class="row align-items-center mb-3">
                                <label class="col-lg-2 col-md-3 col-form-label">Durum</label>
                                <div class="col-lg-10 col-md-9">
                                    <div>
                                        @foreach (Status::cases() as $type)
                                            <label class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="status"
                                                    value="{{ $type->value }}" {{ 1 == $type->value ? 'checked' : '' }}>
                                                <span class="form-check-label">{{ Status::getTitle($type->value) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="title" class="col-form-label col-lg-2 col-md-3">Vergi Oranı Adı</label>
                                <div class="col-lg-10 col-md-9">
                                    <div class="input-group">
                                        <div class="input-group-text rounded-0 border-end-0 bg-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-tag" viewBox="0 0 16 16">
                                                <path
                                                    d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0">
                                                </path>
                                                <path
                                                    d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1m0 5.586 7 7L13.586 9l-7-7H2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" name="title" id="title"
                                            class="form-control rounded-0 border-start-0"
                                            placeholder="Vergi Oranı Adını Giriniz" value="{{ old('title') }}" required>
                                    </div>
                                    <span class="form-hint small">Vergi oranı adını giriniz</span>
                                    @error('title')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-form-label col-lg-2 col-md-3">Bilgiler</label>
                                <div class="col-lg-10 col-md-9">
                                    <div class="row mb-3">
                                        <div class="col-lg-6 mb-1">
                                            <label for="code" class="col-form-label">Kısa Kod</label>
                                            <div class="input-group">
                                                <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-alphabet-uppercase"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M1.226 10.88H0l2.056-6.26h1.42l2.047 6.26h-1.29l-.48-1.61H1.707l-.48 1.61ZM2.76 5.818h-.054l-.75 2.532H3.51zm3.217 5.062V4.62h2.56c1.09 0 1.808.582 1.808 1.54 0 .762-.444 1.22-1.05 1.372v.055c.736.074 1.365.587 1.365 1.528 0 1.119-.89 1.766-2.133 1.766zM7.18 5.55v1.675h.8c.812 0 1.171-.308 1.171-.853 0-.51-.328-.822-.898-.822zm0 2.537V9.95h.903c.951 0 1.342-.312 1.342-.909 0-.591-.382-.954-1.095-.954zm5.089-.711v.775c0 1.156.49 1.803 1.347 1.803.705 0 1.163-.454 1.212-1.096H16v.12C15.942 10.173 14.95 11 13.607 11c-1.648 0-2.573-1.073-2.573-2.849v-.78c0-1.775.934-2.871 2.573-2.871 1.347 0 2.34.849 2.393 2.087v.115h-1.172c-.05-.665-.516-1.156-1.212-1.156-.849 0-1.347.67-1.347 1.83">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <input type="text" name="code" id="code"
                                                    class="form-control rounded-0 border-start-0"
                                                    placeholder="Vergi Oranı Kısa Kodunu Giriniz"
                                                    value="{{ old('code') }}" required>
                                            </div>
                                            <span class="form-hint small">Vergi oranını sembolize eden kısa kodu
                                                giriniz</span>
                                            @error('code')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-6 mb-1">
                                            <label for="value" class="col-form-label">Vergi Oranı</label>
                                            <div class="input-group">
                                                <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-percent" viewBox="0 0 16 16">
                                                        <path
                                                            d="M13.442 2.558a.625.625 0 0 1 0 .884l-10 10a.625.625 0 1 1-.884-.884l10-10a.625.625 0 0 1 .884 0M4.5 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5m7 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <input id="value" type="text" name="value"
                                                    class="form-control rounded-0 border-start-0"
                                                    placeholder="Vergi Oranı Değerini Giriniz" value="{{ old('value') }}"
                                                    required>
                                            </div>
                                            <span class="form-hint small">Vergi oranı değerini giriniz</span>
                                            @error('value')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-6 mb-1">
                                            <select class="form-select rounded-0 tom-select" name="regions[0][country_id]"
                                                id="country_id">
                                                @foreach ($countries as $countryItem)
                                                    <option value="{{ $countryItem->id }}"
                                                        {{ old('regions.0.country_id') }}>
                                                        {{ $countryItem->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="form-hint small">Lütfen ülke seçiniz.</span>
                                            @error('regions.0.country_id')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <select class="form-select rounded-0 tom-select" name="regions[0][state_id]"
                                                id="state_id"
                                                data-current-state="">
                                                <option value="" selected>

                                                </option>
                                            </select>
                                            <span class="form-hint small">Lütfen şehir seçiniz.</span>
                                            @error('regions.0.state_id')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-10 col-mg-9 offset-lg-2 offset-md-3">
                                    <div class="d-flex align-items-center justify-content-between w-100">
                                        <button type="submit" class="btn rounded-1 px-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="currentColor" class="bi bi-floppy" viewBox="0 0 20 20">
                                                <path d="M11 2H9v3h2z" />
                                                <path
                                                    d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z" />
                                            </svg>
                                            KAYDET
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ana JS dosyası TomSelect'leri initialize ettikten sonra çalışacak
            setTimeout(() => {
                // Tom-select instanceları al
                const countrySelect = document.getElementById('country_id').tomselect;
                const stateSelect = document.getElementById('state_id').tomselect;

                // Başlangıçta seçili ülke varsa şehirleri yükle
                const initialCountryId = countrySelect.getValue();
                if (initialCountryId) {
                    getStates(initialCountryId).then(() => {
                        // Mevcut state_id varsa seç
                        const currentStateId = document.getElementById('state_id').dataset
                            .currentState;
                        if (currentStateId) {
                            stateSelect.setValue(currentStateId);
                        }
                    });
                }

                // Ülke değiştiğinde şehirleri güncelle
                countrySelect.on('change', function(value) {
                    getStates(value);
                });

                async function getStates(countryId) {
                    if (!countryId) {
                        stateSelect.clear();
                        stateSelect.clearOptions();
                        stateSelect.addOption({
                            value: '',
                            text: 'Şehir Seçiniz'
                        });
                        stateSelect.refreshOptions();
                        return;
                    }

                    try {
                        const response = await fetch(
                            `/panel/tools/config/countries/states?country_id=${countryId}`);
                        const states = await response.json();

                        stateSelect.clear();
                        stateSelect.clearOptions();
                        stateSelect.addOption({
                            value: '',
                            text: 'Şehir Seçiniz'
                        });

                        states.forEach(state => {
                            stateSelect.addOption({
                                value: state.id,
                                text: state.name
                            });
                        });
                        stateSelect.refreshOptions();
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            }, 100); // Ana JS'in TomSelect'leri initialize etmesi için kısa bir bekleme
        });
    </script>
@endsection
