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
                    <h3 class="form-title border-bottom mb-3 pb-3">Hesap Bilgileri</h3>
                    <form action="{{ route('app.account.update') }}" method="post">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label required">Firma / Kişi Adı</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-building-check" viewBox="0 0 16 16">
                                            <path
                                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514" />
                                            <path
                                                d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1z" />
                                            <path
                                                d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="invoice_name" id="invoice_name"
                                        class="form-control rounded-0 border-start-0" placeholder="Firma/Kişi Adı"
                                        value="{{ old('invoice_name', $account->invoice_name ?? '') }}" required>
                                </div>
                                <span class="form-hint small">Lütfen firma ya da kişi adını giriniz.</span>
                                @error('invoice_name')
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
                                            <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-123" viewBox="0 0 16 16">
                                                    <path
                                                        d="M2.873 11.297V4.142H1.699L0 5.379v1.137l1.64-1.18h.06v5.961zm3.213-5.09v-.063c0-.618.44-1.169 1.196-1.169.676 0 1.174.44 1.174 1.106 0 .624-.42 1.101-.807 1.526L4.99 10.553v.744h4.78v-.99H6.643v-.069L8.41 8.252c.65-.724 1.237-1.332 1.237-2.27C9.646 4.849 8.723 4 7.308 4c-1.573 0-2.36 1.064-2.36 2.15v.057zm6.559 1.883h.786c.823 0 1.374.481 1.379 1.179.01.707-.55 1.216-1.421 1.21-.77-.005-1.326-.419-1.379-.953h-1.095c.042 1.053.938 1.918 2.464 1.918 1.478 0 2.642-.839 2.62-2.144-.02-1.143-.922-1.651-1.551-1.714v-.063c.535-.09 1.347-.66 1.326-1.678-.026-1.053-.933-1.855-2.359-1.845-1.5.005-2.317.88-2.348 1.898h1.116c.032-.498.498-.944 1.206-.944.703 0 1.206.435 1.206 1.07.005.64-.504 1.106-1.2 1.106h-.75z" />
                                                </svg>
                                            </div>
                                            <input type="text" name="tax_number" id="tax_number"
                                                class="form-control rounded-0 border-start-0" placeholder="Vergi Numarası"
                                                value="{{ old('tax_number', $account->tax_number ?? '') }}">
                                        </div>
                                        <span class="form-hint small">Lütfen vergi numaranızı giriniz.
                                            Bireysel müşteriler T.C. kimlik numaralarını girmelidir.</span>
                                        @error('tax_number')
                                            <div class="invalid-feedback" role="alert">{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="input-group">
                                            <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-bank" viewBox="0 0 16 16">
                                                    <path
                                                        d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1zM2 6v7h1V6zm2 0v7h2.5V6zm3.5 0v7h1V6zm2 0v7H12V6zM13 6v7h1V6zm2-1V4H1v1zm-.39 9H1.39l-.25 1h13.72z" />
                                                </svg>
                                            </div>
                                            <input type="text" name="tax_office" id="tax_office"
                                                class="form-control rounded-0 border-start-0" placeholder="Vergi Dairesi"
                                                value="{{ old('tax_office', $account->tax_office ?? '') }}">
                                        </div>
                                        <span class="form-hint small">Lütfen vergi dairenizi giriniz.
                                            Bireysel müşteriler bu kısmı boş bırakabilir.</span>
                                        @error('tax_office')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Mersis No</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-list-ol" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5" />
                                            <path
                                                d="M1.713 11.865v-.474H2c.217 0 .363-.137.363-.317 0-.185-.158-.31-.361-.31-.223 0-.367.152-.373.31h-.59c.016-.467.373-.787.986-.787.588-.002.954.291.957.703a.595.595 0 0 1-.492.594v.033a.615.615 0 0 1 .569.631c.003.533-.502.8-1.051.8-.656 0-1-.37-1.008-.794h.582c.008.178.186.306.422.309.254 0 .424-.145.422-.35-.002-.195-.155-.348-.414-.348h-.3zm-.004-4.699h-.604v-.035c0-.408.295-.844.958-.844.583 0 .96.326.96.756 0 .389-.257.617-.476.848l-.537.572v.03h1.054V9H1.143v-.395l.957-.99c.138-.142.293-.304.293-.508 0-.18-.147-.32-.342-.32a.33.33 0 0 0-.342.338zM2.564 5h-.635V2.924h-.031l-.598.42v-.567l.629-.443h.635z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="mersis" id="mersis"
                                        class="form-control rounded-0 border-start-0" placeholder="Mersis Numarası"
                                        value="{{ old('mersis', $account->mersis ?? '') }}">
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
                                                class="input-group-text rounded-0 align-items-start bg-white border-end-0 pe-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                                    <path
                                                        d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                                                    <path
                                                        d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                                </svg>
                                            </span>
                                            <textarea class="form-control rounded-0 border-start-0" name="address" rows="2" placeholder="Adresiniz"
                                                required>{{ old('address', $account->address ?? '') }}</textarea>
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
                                            <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-1-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M9.283 4.002V12H7.971V5.338h-.065L6.072 6.656V5.385l1.899-1.383z" />
                                                    <path
                                                        d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
                                                </svg>
                                            </div>
                                            <input type="text" name="zip_code" id="zip_code"
                                                class="form-control rounded-0 border-start-0" placeholder="Posta Kodu"
                                                value="{{ old('zip_code', $account->zip_code ?? '') }}" required>
                                        </div>
                                        <span class="form-hint small">Lütfen posta kodunu giriniz.</span>
                                        @error('zip_code')
                                            <div class="invalid-feedback" role="alert">{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="input-group">
                                            <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pin-map" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M3.1 11.2a.5.5 0 0 1 .4-.2H6a.5.5 0 0 1 0 1H3.75L1.5 15h13l-2.25-3H10a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 .4.2l3 4a.5.5 0 0 1-.4.8H.5a.5.5 0 0 1-.4-.8z" />
                                                    <path fill-rule="evenodd"
                                                        d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999z" />
                                                </svg>
                                            </div>
                                            <input type="text" name="district" id="district"
                                                class="form-control rounded-0 border-start-0" placeholder="İlçe"
                                                value="{{ old('district', $account->district ?? '') }}" required>
                                        </div>
                                        <span class="form-hint small">Lütfen ilçe adını giriniz.</span>
                                        @error('district')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-6 mb-1">
                                        <select class="form-select rounded-0 tom-select" name="country_id"
                                            id="country_id">
                                            @foreach ($countries as $countryItem)
                                                <option value="{{ $countryItem->id }}"
                                                    {{ (old('country_id') ?? $account->country_id) == $countryItem->id ? 'selected' : '' }}>
                                                    {{ $countryItem->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="form-hint small">Lütfen ülke seçiniz.</span>
                                        @error('country_id')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <select class="form-select rounded-0 tom-select" name="state_id" id="state_id"
                                            data-current-state="{{ $account->state_id }}">
                                            @if ($account->state)
                                                <option value="{{ $account->state->id }}" selected>
                                                    {{ $account->state->name }}
                                                </option>
                                            @endif
                                        </select>
                                        <span class="form-hint small">Lütfen şehir seçiniz.</span>
                                        @error('city')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
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
                                            <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                                    <path
                                                        d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
                                                </svg>
                                            </div>
                                            <input type="email" name="email" id="email"
                                                class="form-control rounded-0 border-start-0" placeholder="E-posta Adresi"
                                                value="{{ old('email', $account->email ?? '') }}" required>
                                        </div>
                                        <span class="form-hint small">Lütfen e-posta adresinizi giriniz. Fatura bilgileri
                                            bu adrese gönderilecektir.</span>
                                        @error('email')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="input-group">
                                            <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-envelope-open" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8.47 1.318a1 1 0 0 0-.94 0l-6 3.2A1 1 0 0 0 1 5.4v.817l5.75 3.45L8 8.917l1.25.75L15 6.217V5.4a1 1 0 0 0-.53-.882zM15 7.383l-4.778 2.867L15 13.117zm-.035 6.88L8 10.082l-6.965 4.18A1 1 0 0 0 2 15h12a1 1 0 0 0 .965-.738ZM1 13.116l4.778-2.867L1 7.383v5.734ZM7.059.435a2 2 0 0 1 1.882 0l6 3.2A2 2 0 0 1 16 5.4V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5.4a2 2 0 0 1 1.059-1.765z" />
                                                </svg>
                                            </div>
                                            <input type="email" name="kep" id="kep"
                                                class="form-control rounded-0 border-start-0" placeholder="Kep Adresi"
                                                value="{{ old('kep', $account->kep ?? '') }}">
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
                                <div class="row">
                                    <div class="col-md-6 mb-1">
                                        <div class="input-group">
                                            <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-telephone-plus" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                                                    <path fill-rule="evenodd"
                                                        d="M12.5 1a.5.5 0 0 1 .5.5V3h1.5a.5.5 0 0 1 0 1H13v1.5a.5.5 0 0 1-1 0V4h-1.5a.5.5 0 0 1 0-1H12V1.5a.5.5 0 0 1 .5-.5" />
                                                </svg>
                                            </div>
                                            <input type="text" name="phone" id="phone"
                                                class="form-control rounded-0 border-start-0"
                                                placeholder="Telefon Numarası"
                                                value="{{ old('phone', $account->phone ?? '') }}">
                                        </div>
                                        <span class="form-hint small">Lütfen telefon numarası giriniz.</span>
                                        @error('phone')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="input-group">
                                            <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-phone" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                                    <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                                                </svg>
                                            </div>
                                            <input type="text" name="gsm" id="gsm"
                                                class="form-control rounded-0 border-start-0"
                                                placeholder="Ek Telefon Numarası"
                                                value="{{ old('gsm', $account->gsm ?? '') }}">
                                        </div>
                                        <span class="form-hint small">Lütfen ek telefon numarası giriniz.</span>
                                        @error('gsm')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-checks m-n4">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 12l5 5l10 -10" />
                                        <path d="M2 12l5 5m5 -5l5 -5" />
                                    </svg>
                                    Bilgileri Kaydet
                                </button>
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
                            `/app/states?country_id=${countryId}`);
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
