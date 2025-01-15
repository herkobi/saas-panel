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
                        <h3 class="form-title border-bottom mb-3 pb-3">Yeni Para Birimi</h3>
                        <form action="{{ route('panel.tools.config.currency.store') }}" method="POST">
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
                                <label for="name" class="col-form-label col-lg-2 col-md-3">Para Birimi Adı</label>
                                <div class="col-lg-10 col-md-9">
                                    <div class="input-group">
                                        <div class="input-group-text rounded-0 border-end-0 bg-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
                                                <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4"></path>
                                                <path
                                                    d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input id="name" type="text" name="name"
                                            class="form-control rounded-0 border-start-0"
                                            placeholder="Para Birimi Adını Giriniz" value="{{ old('name') }}" required>
                                    </div>
                                    <span class="form-hint small">Para birimi adını giriniz</span>
                                    @error('name')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-5">
                                <label class="col-form-label col-lg-2 col-md-3">Bilgiler</label>
                                <div class="col-lg-10 col-md-9">
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-1">
                                            <label for="symbol" class="col-form-label">Sembol</label>
                                            <div class="input-group">
                                                <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-cash-stack" viewBox="0 0 16 16">
                                                        <path
                                                            d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4">
                                                        </path>
                                                        <path
                                                            d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <input id="symbol" type="text" name="symbol"
                                                    class="form-control rounded-0 border-start-0" placeholder="Sembol"
                                                    value="{{ old('symbol') }}" required>
                                            </div>
                                            <span class="form-hint small">Para birimine ait sembolü giriniz. Örnek:
                                                Türk Lirasının sembolü: ₺</span>
                                            @error('symbol')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label for="symbol_position" class="col-form-label">Sembol
                                                Konumu</label>
                                            <select id="symbol_position" name="symbol_position" class="form-select"
                                                required>
                                                <option value="left">Solda</option>
                                                <option value="right">Sağda</option>
                                                <option value="left_space">Solda Boşlukla</option>
                                                <option value="right_space">Sağda Boşlukla</option>
                                            </select>
                                            <span class="form-hint small">Para birimine ait sembolün konumunu
                                                belirtin.</span>
                                            @error('symbol_position')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3 mb-1">
                                            <label for="thousands_separator" class="col-form-label">Binlik
                                                Ayırıcı</label>
                                            <div class="input-group">
                                                <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-input-cursor-text"
                                                        viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M5 2a.5.5 0 0 1 .5-.5c.862 0 1.573.287 2.06.566.174.099.321.198.44.286.119-.088.266-.187.44-.286A4.17 4.17 0 0 1 10.5 1.5a.5.5 0 0 1 0 1c-.638 0-1.177.213-1.564.434a3.5 3.5 0 0 0-.436.294V7.5H9a.5.5 0 0 1 0 1h-.5v4.272c.1.08.248.187.436.294.387.221.926.434 1.564.434a.5.5 0 0 1 0 1 4.17 4.17 0 0 1-2.06-.566A5 5 0 0 1 8 13.65a5 5 0 0 1-.44.285 4.17 4.17 0 0 1-2.06.566.5.5 0 0 1 0-1c.638 0 1.177-.213 1.564-.434.188-.107.335-.214.436-.294V8.5H7a.5.5 0 0 1 0-1h.5V3.228a3.5 3.5 0 0 0-.436-.294A3.17 3.17 0 0 0 5.5 2.5.5.5 0 0 1 5 2">
                                                        </path>
                                                        <path
                                                            d="M10 5h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4v1h4a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-4zM6 5V4H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v-1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <input id="thousands_separator" type="text" name="thousands_separator"
                                                    class="form-control rounded-0 border-start-0"
                                                    placeholder="Binlik Ayırıcı" value="{{ old('thousands_separator') }}"
                                                    required>
                                            </div>
                                            <span class="form-hint small">Görüntülenen fiyatlarda binlik ayırıcı
                                                ayarı</span>
                                            @error('thousands_separator')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <label for="decimal_separator" class="col-form-label">Ondalık
                                                Ayırıcı</label>
                                            <div class="input-group">
                                                <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-input-cursor"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M10 5h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4v1h4a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-4zM6 5V4H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v-1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z">
                                                        </path>
                                                        <path fill-rule="evenodd"
                                                            d="M8 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13A.5.5 0 0 1 8 1">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <input id="decimal_separator" type="text" name="decimal_separator"
                                                    class="form-control rounded-0 border-start-0"
                                                    placeholder="Ondalık Ayırıcı" value="{{ old('decimal_separator') }}"
                                                    required>
                                            </div>
                                            <span class="form-hint small">Görüntülenen fiyatlarda ondalık ayırıcı
                                                işaretini giriniz</span>
                                            @error('decimal_separator')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <label for="decimal_digits" class="col-form-label">Ondalık
                                                Sayısı</label>
                                            <div class="input-group">
                                                <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-123" viewBox="0 0 16 16">
                                                        <path
                                                            d="M2.873 11.297V4.142H1.699L0 5.379v1.137l1.64-1.18h.06v5.961zm3.213-5.09v-.063c0-.618.44-1.169 1.196-1.169.676 0 1.174.44 1.174 1.106 0 .624-.42 1.101-.807 1.526L4.99 10.553v.744h4.78v-.99H6.643v-.069L8.41 8.252c.65-.724 1.237-1.332 1.237-2.27C9.646 4.849 8.723 4 7.308 4c-1.573 0-2.36 1.064-2.36 2.15v.057zm6.559 1.883h.786c.823 0 1.374.481 1.379 1.179.01.707-.55 1.216-1.421 1.21-.77-.005-1.326-.419-1.379-.953h-1.095c.042 1.053.938 1.918 2.464 1.918 1.478 0 2.642-.839 2.62-2.144-.02-1.143-.922-1.651-1.551-1.714v-.063c.535-.09 1.347-.66 1.326-1.678-.026-1.053-.933-1.855-2.359-1.845-1.5.005-2.317.88-2.348 1.898h1.116c.032-.498.498-.944 1.206-.944.703 0 1.206.435 1.206 1.07.005.64-.504 1.106-1.2 1.106h-.75z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <input id="decimal_digits" type="number" name="decimal_digits"
                                                    min="0" step="1"
                                                    class="form-control rounded-0 border-start-0" value="2"
                                                    placeholder="Ondalık Sayısı" value="{{ old('decimal_digits') }}"
                                                    required>
                                            </div>
                                            <span class="form-hint small">Görüntülenen fiyatlarda ondalık nokta
                                                sayısını giriniz</span>
                                            @error('decimal_digits')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label for="iso_code" class="col-form-label">ISO Kodu</label>
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
                                                <input id="iso_code" type="text" name="iso_code"
                                                    class="form-control rounded-0 border-start-0" placeholder="ISO Kodu"
                                                    value="{{ old('iso_code') }}" required>
                                            </div>
                                            <span class="form-hint small">Para birimine ait ISO kodunu giriniz. ISO
                                                kod listesine <a href="https://en.wikipedia.org/wiki/ISO_4217"
                                                    target="_blank" rel="noopener">bu adresten</a>
                                                ulaşabilirsiniz.</span>
                                            @error('iso_code')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
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
@endsection
