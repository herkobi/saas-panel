@extends('layouts.app')
@section('content')
    <div class="container h-100">
        @include('user.include.header', [
            'title' => 'Hesap Bilgileri',
        ])
        <div class="page-content flex-grow-1 d-flex flex-column shadow-sm h-100">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="page-menu rounded-2">
                                @include('user.account.include.navigation')
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex align-items-center justify-content-between border-bottom mb-4 pb-3 w-100">
                                <h2>Fatura / İletişim Bilgilerini Düzenle</h2>
                                <a href="{{ route('app.account.invoicedetail') }}" class="btn btn-sm btn-primary"
                                    title="Fatura Bilgileri">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-address-book m-n4">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M20 6v12a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2z" />
                                        <path d="M10 16h6" />
                                        <path d="M13 11m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M4 8h3" />
                                        <path d="M4 12h3" />
                                        <path d="M4 16h3" />
                                    </svg>
                                    Fatura Bilgileri
                                </a>
                            </div>
                            <div class="card border-0 mb-5">
                                <div class="card-body p-0">
                                    <form action="{{ route('app.account.invoicedetail.update', $detail->id) }}"
                                        method="post">
                                        @csrf
                                        <div class="row mb-3">
                                            <label class="col-md-3 col-form-label required">Fatura Adı</label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white border-end-0 pe-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-text-size">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M3 7v-2h13v2"></path>
                                                            <path d="M10 5v14"></path>
                                                            <path d="M12 19h-4"></path>
                                                            <path d="M15 13v-1h6v1"></path>
                                                            <path d="M18 12v7"></path>
                                                            <path d="M17 19h2"></path>
                                                        </svg>
                                                    </span>
                                                    <input type="text" name="title"
                                                        class="form-control border-start-0 @error('title') is-invalid @enderror"
                                                        placeholder="Fatura Adı" value="{{ old('title', $detail->title) }}"
                                                        autocomplete="off" required>
                                                </div>
                                                <span class="form-hint small">Lütfen fatura bilgilerini tanımlayacak bir
                                                    isim giriniz. Örnek: Kurumsal, bireysel vb.</span>
                                                @error('title')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-3 col-form-label required">Firma / Kişi Adı</label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white border-end-0 pe-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-address-book">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M20 6v12a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2z" />
                                                            <path d="M10 16h6" />
                                                            <path d="M13 11m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                            <path d="M4 8h3" />
                                                            <path d="M4 12h3" />
                                                            <path d="M4 16h3" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" name="invoiceName"
                                                        class="form-control border-start-0 @error('invoiceName') is-invalid @enderror"
                                                        placeholder="Firma / Kişi Adı"
                                                        value="{{ old('invoiceName', $detail->invoiceName) }}"
                                                        autocomplete="off" required>
                                                </div>
                                                <span class="form-hint small">Lütfen firma ya da kişi adını giriniz.</span>
                                                @error('invoiceName')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label class="col-md-3 col-form-label required">Vergi Numarası / Dairesi</label>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-white border-end-0 pe-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-number">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M4 17v-10l7 10v-10" />
                                                                    <path d="M15 17h5" />
                                                                    <path
                                                                        d="M17.5 10m-2.5 0a2.5 3 0 1 0 5 0a2.5 3 0 1 0 -5 0" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="taxNumber"
                                                                class="form-control border-start-0 @error('taxNumber') is-invalid @enderror"
                                                                placeholder="Vergi Numarası"
                                                                value="{{ old('taxNumber', $detail->taxNumber) }}"
                                                                autocomplete="off" required>
                                                        </div>
                                                        <span class="form-hint small">Lütfen vergi numaranızı giriniz.
                                                            Bireysel müşteriler T.C. kimlik numaralarını girmelidir.</span>
                                                        @error('taxNumber')
                                                            <div class="invalid-feedback" role="alert">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-white border-end-0 pe-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-building">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M3 21l18 0" />
                                                                    <path d="M9 8l1 0" />
                                                                    <path d="M9 12l1 0" />
                                                                    <path d="M9 16l1 0" />
                                                                    <path d="M14 8l1 0" />
                                                                    <path d="M14 12l1 0" />
                                                                    <path d="M14 16l1 0" />
                                                                    <path
                                                                        d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="taxOffice"
                                                                class="form-control border-start-0 @error('taxOffice') is-invalid @enderror"
                                                                placeholder="Vergi Dairesi"
                                                                value="{{ old('taxOffice', $detail->taxOffice) }}"
                                                                autocomplete="off">
                                                        </div>
                                                        <span class="form-hint small">Lütfen vergi dairenizi giriniz.
                                                            Bireysel müşteriler bu kısmı boş bırakabilir.</span>
                                                        @error('taxOffice')
                                                            <div class="invalid-feedback" role="alert">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-3 col-form-label">Mersis No</label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white border-end-0 pe-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-numbers">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M8 10v-7l-2 2" />
                                                            <path d="M6 16a2 2 0 1 1 4 0c0 .591 -.601 1.46 -1 2l-3 3h4" />
                                                            <path d="M15 14a2 2 0 1 0 2 -2a2 2 0 1 0 -2 -2" />
                                                            <path d="M6.5 10h3" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" name="mersis"
                                                        class="form-control border-start-0 @error('mersis') is-invalid @enderror"
                                                        placeholder="Mersis No"
                                                        value="{{ old('mersis', $detail->mersis) }}" autocomplete="off">
                                                </div>
                                                <span class="form-hint small">Varsa lütfen MERSİS numaranızı giriniz.
                                                    Bireysel müşteriler bu alanı boş bırakabilir.</span>
                                                @error('mersis')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label class="col-md-3 col-form-label required">Adres Bilgisi</label>
                                            <div class="col-md-9">
                                                <div class="row mb-1">
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <span
                                                                class="input-group-text align-items-start bg-white border-end-0 pe-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-map-2">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                                                                    <path d="M9 4v13" />
                                                                    <path d="M15 7v5.5" />
                                                                    <path
                                                                        d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" />
                                                                    <path d="M19 18v.01" />
                                                                </svg>
                                                            </span>
                                                            <textarea class="form-control border-start-0 @error('taxNumber') is-invalid @enderror" name="address" rows="2"
                                                                placeholder="Adresiniz" required>{{ old('address', $detail->address) }}</textarea>
                                                        </div>
                                                        <span class="form-hint small">Lütfen fatura adresinizi
                                                            giriniz.</span>
                                                        @error('address')
                                                            <div class="invalid-feedback" role="alert">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-white border-end-0 pe-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-zip">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M16 16v-8h2a2 2 0 1 1 0 4h-2" />
                                                                    <path d="M12 8v8" />
                                                                    <path d="M4 8h4l-4 8h4" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="zipCode"
                                                                class="form-control border-start-0 @error('zipCode') is-invalid @enderror"
                                                                placeholder="Posta Kodu"
                                                                value="{{ old('zipCode', $detail->zipCode) }}"
                                                                autocomplete="off" required>
                                                        </div>
                                                        <span class="form-hint small">Lütfen posta kodunu giriniz.</span>
                                                        @error('zipCode')
                                                            <div class="invalid-feedback" role="alert">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-white border-end-0 pe-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-fountain">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M9 16v-5a2 2 0 1 0 -4 0" />
                                                                    <path d="M15 16v-5a2 2 0 1 1 4 0" />
                                                                    <path d="M12 16v-10a3 3 0 0 1 6 0" />
                                                                    <path d="M6 6a3 3 0 0 1 6 0" />
                                                                    <path
                                                                        d="M3 16h18v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-2z" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="state"
                                                                class="form-control border-start-0 @error('state') is-invalid @enderror"
                                                                placeholder="İlçe"
                                                                value="{{ old('state', $detail->state) }}"
                                                                autocomplete="off" required>
                                                        </div>
                                                        <span class="form-hint small">Lütfen ilçe adını giriniz.</span>
                                                        @error('state')
                                                            <div class="invalid-feedback" role="alert">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6 mb-1">
                                                        <select class="form-select" name="city">
                                                            <option>Şehir Seçiniz</option>
                                                            <option value="Adana" data-plaka="1"
                                                                {{ 'Adana' == $detail->city ? 'selected' : '' }}>Adana
                                                            </option>
                                                            <option value="Adıyaman" data-plaka="2"
                                                                {{ 'Adıyaman' == $detail->city ? 'selected' : '' }}>
                                                                Adıyaman</option>
                                                            <option value="Afyonkarahisar" data-plaka="3"
                                                                {{ 'Afyonkarahisar' == $detail->city ? 'selected' : '' }}>
                                                                Afyonkarahisar
                                                            </option>
                                                            <option value="Ağrı" data-plaka="4"
                                                                {{ 'Ağrı' == $detail->city ? 'selected' : '' }}>Ağrı
                                                            </option>
                                                            <option value="Aksaray" data-plaka="68"
                                                                {{ 'Aksaray' == $detail->city ? 'selected' : '' }}>Aksaray
                                                            </option>
                                                            <option value="Amasya" data-plaka="5"
                                                                {{ 'Amasya' == $detail->city ? 'selected' : '' }}>Amasya
                                                            </option>
                                                            <option value="Ankara" data-plaka="6"
                                                                {{ 'Ankara' == $detail->city ? 'selected' : '' }}>Ankara
                                                            </option>
                                                            <option value="Antalya" data-plaka="7"
                                                                {{ 'Antalya' == $detail->city ? 'selected' : '' }}>Antalya
                                                            </option>
                                                            <option value="Ardahan" data-plaka="75"
                                                                {{ 'Ardahan' == $detail->city ? 'selected' : '' }}>Ardahan
                                                            </option>
                                                            <option value="Artvin" data-plaka="8"
                                                                {{ 'Artvin' == $detail->city ? 'selected' : '' }}>Artvin
                                                            </option>
                                                            <option value="Aydın" data-plaka="9"
                                                                {{ 'Aydın' == $detail->city ? 'selected' : '' }}>Aydın
                                                            </option>
                                                            <option value="Balıkesir" data-plaka="10"
                                                                {{ 'Balıkesir' == $detail->city ? 'selected' : '' }}>
                                                                Balıkesir</option>
                                                            <option value="Bartın" data-plaka="74"
                                                                {{ 'Bartın' == $detail->city ? 'selected' : '' }}>Bartın
                                                            </option>
                                                            <option value="Batman" data-plaka="72"
                                                                {{ 'Batman' == $detail->city ? 'selected' : '' }}>Batman
                                                            </option>
                                                            <option value="Bayburt" data-plaka="69"
                                                                {{ 'Bayburt' == $detail->city ? 'selected' : '' }}>Bayburt
                                                            </option>
                                                            <option value="Bilecik" data-plaka="11"
                                                                {{ 'Bilecik' == $detail->city ? 'selected' : '' }}>Bilecik
                                                            </option>
                                                            <option value="Bingöl" data-plaka="12"
                                                                {{ 'Bingöl' == $detail->city ? 'selected' : '' }}>Bingöl
                                                            </option>
                                                            <option value="Bitlis" data-plaka="13"
                                                                {{ 'Bitlis' == $detail->city ? 'selected' : '' }}>Bitlis
                                                            </option>
                                                            <option value="Bolu" data-plaka="14"
                                                                {{ 'Bolu' == $detail->city ? 'selected' : '' }}>Bolu
                                                            </option>
                                                            <option value="Burdur" data-plaka="15"
                                                                {{ 'Burdur' == $detail->city ? 'selected' : '' }}>Burdur
                                                            </option>
                                                            <option value="Bursa" data-plaka="16"
                                                                {{ 'Bursa' == $detail->city ? 'selected' : '' }}>Bursa
                                                            </option>
                                                            <option value="Çanakkale" data-plaka="17"
                                                                {{ 'Çanakkale' == $detail->city ? 'selected' : '' }}>
                                                                Çanakkale</option>
                                                            <option value="Çankırı" data-plaka="18"
                                                                {{ 'Çankırı' == $detail->city ? 'selected' : '' }}>Çankırı
                                                            </option>
                                                            <option value="Çorum" data-plaka="19"
                                                                {{ 'Çorum' == $detail->city ? 'selected' : '' }}>Çorum
                                                            </option>
                                                            <option value="Denizli" data-plaka="20"
                                                                {{ 'Denizli' == $detail->city ? 'selected' : '' }}>Denizli
                                                            </option>
                                                            <option value="Diyarbakır" data-plaka="21"
                                                                {{ 'Diyarbakır' == $detail->city ? 'selected' : '' }}>
                                                                Diyarbakır</option>
                                                            <option value="Düzce" data-plaka="81"
                                                                {{ 'Düzce' == $detail->city ? 'selected' : '' }}>Düzce
                                                            </option>
                                                            <option value="Edirne" data-plaka="22"
                                                                {{ 'Edirne' == $detail->city ? 'selected' : '' }}>Edirne
                                                            </option>
                                                            <option value="Elazığ" data-plaka="23"
                                                                {{ 'Elazığ' == $detail->city ? 'selected' : '' }}>Elazığ
                                                            </option>
                                                            <option value="Erzincan" data-plaka="24"
                                                                {{ 'Erzincan' == $detail->city ? 'selected' : '' }}>
                                                                Erzincan</option>
                                                            <option value="Erzurum" data-plaka="25"
                                                                {{ 'Erzurum' == $detail->city ? 'selected' : '' }}>Erzurum
                                                            </option>
                                                            <option value="Eskişehir" data-plaka="26"
                                                                {{ 'Eskişehir' == $detail->city ? 'selected' : '' }}>
                                                                Eskişehir</option>
                                                            <option value="Gaziantep" data-plaka="27"
                                                                {{ 'Gaziantep' == $detail->city ? 'selected' : '' }}>
                                                                Gaziantep</option>
                                                            <option value="Giresun" data-plaka="28"
                                                                {{ 'Giresun' == $detail->city ? 'selected' : '' }}>Giresun
                                                            </option>
                                                            <option value="Gümüşhane" data-plaka="29"
                                                                {{ 'Gümüşhane' == $detail->city ? 'selected' : '' }}>
                                                                Gümüşhane</option>
                                                            <option value="Hakkari" data-plaka="30"
                                                                {{ 'Hakkari' == $detail->city ? 'selected' : '' }}>Hakkari
                                                            </option>
                                                            <option value="Hatay" data-plaka="31"
                                                                {{ 'Hatay' == $detail->city ? 'selected' : '' }}>Hatay
                                                            </option>
                                                            <option value="Iğdır" data-plaka="76"
                                                                {{ 'Iğdır' == $detail->city ? 'selected' : '' }}>Iğdır
                                                            </option>
                                                            <option value="Isparta" data-plaka="32"
                                                                {{ 'Isparta' == $detail->city ? 'selected' : '' }}>Isparta
                                                            </option>
                                                            <option value="İstanbul" data-plaka="34"
                                                                {{ 'İstanbul' == $detail->city ? 'selected' : '' }}>
                                                                İstanbul</option>
                                                            <option value="İzmir" data-plaka="35"
                                                                {{ 'İzmir' == $detail->city ? 'selected' : '' }}>İzmir
                                                            </option>
                                                            <option value="Kahramanmaraş" data-plaka="46"
                                                                {{ 'Kahramanmaraş' == $detail->city ? 'selected' : '' }}>
                                                                Kahramanmaraş
                                                            </option>
                                                            <option value="Karabük" data-plaka="78"
                                                                {{ 'Karabük' == $detail->city ? 'selected' : '' }}>Karabük
                                                            </option>
                                                            <option value="Karaman" data-plaka="70"
                                                                {{ 'Karaman' == $detail->city ? 'selected' : '' }}>Karaman
                                                            </option>
                                                            <option value="Kars" data-plaka="36"
                                                                {{ 'Kars' == $detail->city ? 'selected' : '' }}>Kars
                                                            </option>
                                                            <option value="Kastamonu" data-plaka="37"
                                                                {{ 'Kastamonu' == $detail->city ? 'selected' : '' }}>
                                                                Kastamonu</option>
                                                            <option value="Kayseri" data-plaka="38"
                                                                {{ 'Kayseri' == $detail->city ? 'selected' : '' }}>Kayseri
                                                            </option>
                                                            <option value="Kırıkkale" data-plaka="71"
                                                                {{ 'Kırıkkale' == $detail->city ? 'selected' : '' }}>
                                                                Kırıkkale</option>
                                                            <option value="Kırklareli" data-plaka="39"
                                                                {{ 'Kırklareli' == $detail->city ? 'selected' : '' }}>
                                                                Kırklareli</option>
                                                            <option value="Kırşehir" data-plaka="40"
                                                                {{ 'Kırşehir' == $detail->city ? 'selected' : '' }}>
                                                                Kırşehir</option>
                                                            <option value="Kilis" data-plaka="79"
                                                                {{ 'Kilis' == $detail->city ? 'selected' : '' }}>Kilis
                                                            </option>
                                                            <option value="Kocaeli" data-plaka="41"
                                                                {{ 'Kocaeli' == $detail->city ? 'selected' : '' }}>Kocaeli
                                                            </option>
                                                            <option value="Konya" data-plaka="42"
                                                                {{ 'Konya' == $detail->city ? 'selected' : '' }}>Konya
                                                            </option>
                                                            <option value="Kütahya" data-plaka="43"
                                                                {{ 'Kütahya' == $detail->city ? 'selected' : '' }}>Kütahya
                                                            </option>
                                                            <option value="Malatya" data-plaka="44"
                                                                {{ 'Malatya' == $detail->city ? 'selected' : '' }}>Malatya
                                                            </option>
                                                            <option value="Manisa" data-plaka="45"
                                                                {{ 'Manisa' == $detail->city ? 'selected' : '' }}>Manisa
                                                            </option>
                                                            <option value="Mardin" data-plaka="47"
                                                                {{ 'Mardin' == $detail->city ? 'selected' : '' }}>Mardin
                                                            </option>
                                                            <option value="Mersin" data-plaka="33"
                                                                {{ 'Mersin' == $detail->city ? 'selected' : '' }}>Mersin
                                                            </option>
                                                            <option value="Muğla" data-plaka="48"
                                                                {{ 'Muğla' == $detail->city ? 'selected' : '' }}>Muğla
                                                            </option>
                                                            <option value="Muş" data-plaka="49"
                                                                {{ 'Muş' == $detail->city ? 'selected' : '' }}>Muş</option>
                                                            <option value="Nevşehir" data-plaka="50"
                                                                {{ 'Nevşehir' == $detail->city ? 'selected' : '' }}>
                                                                Nevşehir</option>
                                                            <option value="Niğde" data-plaka="51"
                                                                {{ 'Niğde' == $detail->city ? 'selected' : '' }}>Niğde
                                                            </option>
                                                            <option value="Ordu" data-plaka="52"
                                                                {{ 'Ordu' == $detail->city ? 'selected' : '' }}>Ordu
                                                            </option>
                                                            <option value="Osmaniye" data-plaka="80"
                                                                {{ 'Osmaniye' == $detail->city ? 'selected' : '' }}>
                                                                Osmaniye</option>
                                                            <option value="Rize" data-plaka="53"
                                                                {{ 'Rize' == $detail->city ? 'selected' : '' }}>Rize
                                                            </option>
                                                            <option value="Sakarya" data-plaka="54"
                                                                {{ 'Sakarya' == $detail->city ? 'selected' : '' }}>Sakarya
                                                            </option>
                                                            <option value="Samsun" data-plaka="55"
                                                                {{ 'Samsun' == $detail->city ? 'selected' : '' }}>Samsun
                                                            </option>
                                                            <option value="Siirt" data-plaka="56"
                                                                {{ 'Siirt' == $detail->city ? 'selected' : '' }}>Siirt
                                                            </option>
                                                            <option value="Sinop" data-plaka="57"
                                                                {{ 'Sinop' == $detail->city ? 'selected' : '' }}>Sinop
                                                            </option>
                                                            <option value="Sivas" data-plaka="58"
                                                                {{ 'Sivas' == $detail->city ? 'selected' : '' }}>Sivas
                                                            </option>
                                                            <option value="Şanlıurfa" data-plaka="63"
                                                                {{ 'Şanlıurfa' == $detail->city ? 'selected' : '' }}>
                                                                Şanlıurfa</option>
                                                            <option value="Şırnak" data-plaka="73"
                                                                {{ 'Şırnak' == $detail->city ? 'selected' : '' }}>Şırnak
                                                            </option>
                                                            <option value="Tekirdağ" data-plaka="59"
                                                                {{ 'Tekirdağ' == $detail->city ? 'selected' : '' }}>
                                                                Tekirdağ</option>
                                                            <option value="Tokat" data-plaka="60"
                                                                {{ 'Tokat' == $detail->city ? 'selected' : '' }}>Tokat
                                                            </option>
                                                            <option value="Trabzon" data-plaka="61"
                                                                {{ 'Trabzon' == $detail->city ? 'selected' : '' }}>Trabzon
                                                            </option>
                                                            <option value="Tunceli" data-plaka="62"
                                                                {{ 'Tunceli' == $detail->city ? 'selected' : '' }}>Tunceli
                                                            </option>
                                                            <option value="Uşak" data-plaka="64"
                                                                {{ 'Uşak' == $detail->city ? 'selected' : '' }}>Uşak
                                                            </option>
                                                            <option value="Van" data-plaka="65"
                                                                {{ 'Van' == $detail->city ? 'selected' : '' }}>Van</option>
                                                            <option value="Yalova" data-plaka="77"
                                                                {{ 'Yalova' == $detail->city ? 'selected' : '' }}>Yalova
                                                            </option>
                                                            <option value="Yozgat" data-plaka="66"
                                                                {{ 'Yozgat' == $detail->city ? 'selected' : '' }}>Yozgat
                                                            </option>
                                                            <option value="Zonguldak" data-plaka="67"
                                                                {{ 'Zonguldak' == $detail->city ? 'selected' : '' }}>
                                                                Zonguldak</option>
                                                        </select>
                                                        <span class="form-hint small">Lütfen şehir seçiniz.</span>
                                                        @error('city')
                                                            <div class="invalid-feedback" role="alert">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-white border-end-0 pe-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-building-estate">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M3 21h18" />
                                                                    <path d="M19 21v-4" />
                                                                    <path
                                                                        d="M19 17a2 2 0 0 0 2 -2v-2a2 2 0 1 0 -4 0v2a2 2 0 0 0 2 2z" />
                                                                    <path
                                                                        d="M14 21v-14a3 3 0 0 0 -3 -3h-4a3 3 0 0 0 -3 3v14" />
                                                                    <path d="M9 17v4" />
                                                                    <path d="M8 13h2" />
                                                                    <path d="M8 9h2" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="country"
                                                                class="form-control border-start-0 @error('country') is-invalid @enderror"
                                                                placeholder="Ülke"
                                                                value="{{ old('country', $detail->country) }}"
                                                                autocomplete="off" readonly>
                                                        </div>
                                                        <span class="form-hint small">Lütfen ülke adını giriniz.</span>
                                                        @error('country')
                                                            <div class="invalid-feedback" role="alert">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label class="col-md-3 col-form-label">E-posta Adresi</label>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-white border-end-0 pe-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path
                                                                        d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                                                                    <path d="M3 7l9 6l9 -6" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" name="email"
                                                                class="form-control border-start-0 @error('email') is-invalid @enderror"
                                                                placeholder="E-posta Adresi"
                                                                value="{{ old('email', $detail->email) }}"
                                                                autocomplete="off" required>
                                                        </div>
                                                        <span class="form-hint small">Lütfen e-posta adresinizi
                                                            giriniz.</span>
                                                        @error('email')
                                                            <div class="invalid-feedback" role="alert">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-white border-end-0 pe-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                    height="20" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-mail-share">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path
                                                                        d="M13 19h-8a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v6" />
                                                                    <path d="M3 7l9 6l9 -6" />
                                                                    <path d="M16 22l5 -5" />
                                                                    <path d="M21 21.5v-4.5h-4.5" />
                                                                </svg>
                                                            </span>
                                                            <input type="email" name="kep"
                                                                class="form-control border-start-0 @error('kep') is-invalid @enderror"
                                                                placeholder="Kep E-posta Adresi"
                                                                value="{{ old('kep', $detail->kep) }}"
                                                                autocomplete="off">
                                                        </div>
                                                        <span class="form-hint small">Varsa lütfen kep e-posta
                                                            adresinizi giriniz. Bireysel müşteriler bu kısmı boş
                                                            bırakabilir.</span>
                                                        @error('kep')
                                                            <div class="invalid-feedback" role="alert">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-3 col-form-label">Telefon Numarası</label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white border-end-0 pe-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" name="phone"
                                                        class="form-control border-start-0 @error('phone') is-invalid @enderror"
                                                        placeholder="Telefon Numarası"
                                                        value="{{ old('phone', $detail->phone) }}" autocomplete="off">
                                                </div>
                                                <span class="form-hint small">Lütfen telefon numarası giriniz.</span>
                                                @error('phone')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-9 offset-md-3">
                                                <div class="d-flex align-items-center justify-content-between w-100">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-checks m-n4">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 12l5 5l10 -10" />
                                                            <path d="M2 12l5 5m5 -5l5 -5" />
                                                        </svg>
                                                        Bilgileri Güncelle
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-trash-x m-n4">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M4 7h16" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                            <path d="M10 12l4 4m0 -4l-4 4" />
                                                        </svg>
                                                        Sil
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
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('app.account.invoicedetail.delete', $detail->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">Dikkat!</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <strong>{{ $detail->title }}</strong> isimli fatura bilgisini silmek üzeresiniz. Bu işlem geri
                        alınamaz. Emin misiniz?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">İptal
                            Et</button>
                        <button type="submit" class="btn btn-sm btn-danger ms-auto">Evet, Fatura Bilgisini Sil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
