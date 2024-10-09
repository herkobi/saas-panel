@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Ayarlar',
    ])
    <div class="page-content flex-grow-1 d-flex flex-column shadow-sm">
        <div class="row flex-grow-1">
            <div class="col-20 col-lg-3 col-md-3">
                <div class="page-menu rounded-2">
                    @include('admin.settings.include.navigation')
                </div>
            </div>
            <div class="col-80 col-lg-9 col-md-9">
                <div class="card h-100 border-0 mb-5">
                    <div class="card-header border-0 bg-white p-0 mb-3">
                        <div class="d-flex align-items-center justify-content-between w-100 border-bottom pb-2">
                            <h1 class="card-title">Sistem Ayarları</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <form method="POST" action="{{ route('panel.settings.system.update') }}">
                                @csrf
                                <div class="col-lg-9 col-md-8">
                                    <div class="row mb-3">
                                        <label class="col-3 col-form-label">Tanımlamalar</label>
                                        <div class="col">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="col-3 col-form-label required">Arayüz Dili</label>
                                                    <div class="col">
                                                        <select class="form-select" name="language">
                                                            <option>Lütfen Seçiniz</option>
                                                            @foreach ($languages as $language)
                                                                <option value="{{ $language->code }}"
                                                                    {{ $language->code == config('panel.language') ? 'selected' : '' }}>
                                                                    {{ $language->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <small class="form-hint">Sistem genel arayüz dilini seçiniz</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="col-form-label required">Konum</label>
                                                    <div class="col">
                                                        <select class="form-select" name="location">
                                                            <option>Lütfen Seçiniz</option>
                                                            @foreach ($locations as $location)
                                                                <option value="{{ $location->code }}"
                                                                    {{ $location->code == config('panel.location') ? 'selected' : '' }}>
                                                                    {{ $location->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <small class="form-hint">Sistem içerisinde kullanılacak genel konumu
                                                        belirtiniz</small>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="col-3 col-form-label required">Para Birimi</label>
                                                    <div class="col">
                                                        <select class="form-select" name="currency">
                                                            <option>Lütfen Seçiniz</option>
                                                            @foreach ($currencies as $currency)
                                                                <option value="{{ $currency->code }}"
                                                                    {{ $currency->code == config('panel.currency') ? 'selected' : '' }}>
                                                                    {{ $currency->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <small class="form-hint">Sistem genelinde kullanılacak para birimini
                                                        seçiniz</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="col-form-label required">Vergi Oranı</label>
                                                    <div class="col">
                                                        <select class="form-select" name="tax">
                                                            <option>Lütfen Seçiniz</option>
                                                            @foreach ($taxes as $tax)
                                                                <option value="{{ $tax->code }}"
                                                                    {{ $tax->code == config('panel.tax') ? 'selected' : '' }}>
                                                                    {{ $tax->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <small class="form-hint">Sistem genelinde kullanılacak vergi bilgisini
                                                        seçiniz</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-3 col-form-label">Tarih/Saat Ayarları</label>
                                        <div class="col">
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <label class="col-form-label required">Zaman Dilimi</label>
                                                    <div class="col">
                                                        <select class="form-select" name="timezone">
                                                            <option>Lütfen Seçiniz</option>
                                                            @foreach (Helper::getTimeZoneList() as $timezone => $timezone_gmt_diff)
                                                                <option value="{{ $timezone }}"
                                                                    {{ $timezone === old('timezone', config('panel.timezone')) ? 'selected' : '' }}>
                                                                    {{ $timezone_gmt_diff }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <small class="form-hint">Sistem genelinde kullanılacak zaman dilimini
                                                        seçiniz</small>
                                                </div>
                                            </div>
                                            <div class="row g-4 mb-3">
                                                <div class="col-md-6">
                                                    <label class="col-form-label border-bottom mb-2 required">Tarih
                                                        Formatı</label>
                                                    <small class="form-hint mb-2">Sistem genelinde kullanılacak tarih
                                                        formatını
                                                        seçiniz</small>
                                                    <div class="col">
                                                        @foreach (Helper::dateformats() as $format)
                                                            <label class="form-check">
                                                                <input type="radio" name="dateformat"
                                                                    class="form-check-input"
                                                                    {{ $format == config('panel.dateformat') ? 'checked' : '' }}
                                                                    value="{{ $format }}">
                                                                <span
                                                                    class="form-check-label">{{ Carbon::now()->format($format) }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="col-form-label border-bottom mb-2 required">Saat
                                                        Formatı</label>
                                                    <small class="form-hint mb-2">Sistem genelinde kullanılacak saat
                                                        formatını
                                                        seçiniz</small>
                                                    <div class="col">
                                                        @foreach (Helper::timeformats() as $format)
                                                            <label class="form-check">
                                                                <input type="radio" name="timeformat"
                                                                    class="form-check-input"
                                                                    {{ $format == config('panel.timeformat') ? 'checked' : '' }}
                                                                    value="{{ $format }}">
                                                                <span
                                                                    class="form-check-label">{{ Carbon::now()->format($format) }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-10 offset-lg-2 col-md-9 offset-md-3">
                                            <button type="submit" id="updateButton"
                                                class="btn btn-primary">Güncelle</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="col-lg-3 col-md-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
