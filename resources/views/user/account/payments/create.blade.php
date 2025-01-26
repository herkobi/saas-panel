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
                    <div class="row align-items-center pb-2">
                        <div class="col-lg-12">
                            <h3 class="form-title border-bottom mb-3 pb-3">Plan Ödemesi</h3>
                        </div>
                    </div>
                    <form action="{{ route('app.account.payment.store') }}" method="post" class="mb-5">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-7">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Fatura Bilgileri</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-building-check"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514" />
                                                            <path
                                                                d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1z" />
                                                            <path
                                                                d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z" />
                                                        </svg>
                                                    </div>
                                                    <input type="text" name="invoice_name" id="invoice_name"
                                                        class="form-control rounded-0 border-start-0"
                                                        placeholder="Firma/Kişi Adı"
                                                        value="{{ old('invoice_name', $tenant->account->invoice_name ?? '') }}"
                                                        required>
                                                </div>
                                                <span class="form-hint small">Lütfen firma ya da kişi adını
                                                    giriniz.</span>
                                                @error('invoice_name')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6 mb-1">
                                                <div class="input-group">
                                                    <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-123"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M2.873 11.297V4.142H1.699L0 5.379v1.137l1.64-1.18h.06v5.961zm3.213-5.09v-.063c0-.618.44-1.169 1.196-1.169.676 0 1.174.44 1.174 1.106 0 .624-.42 1.101-.807 1.526L4.99 10.553v.744h4.78v-.99H6.643v-.069L8.41 8.252c.65-.724 1.237-1.332 1.237-2.27C9.646 4.849 8.723 4 7.308 4c-1.573 0-2.36 1.064-2.36 2.15v.057zm6.559 1.883h.786c.823 0 1.374.481 1.379 1.179.01.707-.55 1.216-1.421 1.21-.77-.005-1.326-.419-1.379-.953h-1.095c.042 1.053.938 1.918 2.464 1.918 1.478 0 2.642-.839 2.62-2.144-.02-1.143-.922-1.651-1.551-1.714v-.063c.535-.09 1.347-.66 1.326-1.678-.026-1.053-.933-1.855-2.359-1.845-1.5.005-2.317.88-2.348 1.898h1.116c.032-.498.498-.944 1.206-.944.703 0 1.206.435 1.206 1.07.005.64-.504 1.106-1.2 1.106h-.75z" />
                                                        </svg>
                                                    </div>
                                                    <input type="text" name="tax_number" id="tax_number"
                                                        class="form-control rounded-0 border-start-0"
                                                        placeholder="Vergi Numarası"
                                                        value="{{ old('tax_number', $tenant->account->tax_number ?? '') }}">
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
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-bank"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1zM2 6v7h1V6zm2 0v7h2.5V6zm3.5 0v7h1V6zm2 0v7H12V6zM13 6v7h1V6zm2-1V4H1v1zm-.39 9H1.39l-.25 1h13.72z" />
                                                        </svg>
                                                    </div>
                                                    <input type="text" name="tax_office" id="tax_office"
                                                        class="form-control rounded-0 border-start-0"
                                                        placeholder="Vergi Dairesi"
                                                        value="{{ old('tax_office', $tenant->account->tax_office ?? '') }}">
                                                </div>
                                                <span class="form-hint small">Lütfen vergi dairenizi giriniz.
                                                    Bireysel müşteriler bu kısmı boş bırakabilir.</span>
                                                @error('tax_office')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <span class="input-group-text rounded-0 border-end-0 bg-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-geo-alt"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                                                            <path
                                                                d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" class="form-control rounded-0 border-start-0"
                                                        name="address" placeholder="Adresiniz"
                                                        value="{{ old('address', $tenant->account->address ?? '') }}"
                                                        required>
                                                </div>
                                                <span class="form-hint small">Lütfen fatura adresinizi giriniz.</span>
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
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-1-square"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M9.283 4.002V12H7.971V5.338h-.065L6.072 6.656V5.385l1.899-1.383z" />
                                                            <path
                                                                d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
                                                        </svg>
                                                    </div>
                                                    <input type="text" name="zip_code" id="zip_code"
                                                        class="form-control rounded-0 border-start-0"
                                                        placeholder="Posta Kodu"
                                                        value="{{ old('zip_code', $tenant->account->zip_code ?? '') }}"
                                                        required>
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
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-pin-map"
                                                            viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd"
                                                                d="M3.1 11.2a.5.5 0 0 1 .4-.2H6a.5.5 0 0 1 0 1H3.75L1.5 15h13l-2.25-3H10a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 .4.2l3 4a.5.5 0 0 1-.4.8H.5a.5.5 0 0 1-.4-.8z" />
                                                            <path fill-rule="evenodd"
                                                                d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999z" />
                                                        </svg>
                                                    </div>
                                                    <input type="text" name="district" id="district"
                                                        class="form-control rounded-0 border-start-0" placeholder="İlçe"
                                                        value="{{ old('district', $tenant->account->district ?? '') }}"
                                                        required>
                                                </div>
                                                <span class="form-hint small">Lütfen ilçe adını giriniz.</span>
                                                @error('district')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6 mb-1">
                                                <select class="form-select rounded-0 tom-select" name="country_id"
                                                    id="country_id">
                                                    @foreach ($countries as $countryItem)
                                                        <option value="{{ $countryItem->id }}"
                                                            {{ (old('country_id') ?? $tenant->account->country_id) == $countryItem->id ? 'selected' : '' }}>
                                                            {{ $countryItem->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="form-hint small">Lütfen ülke seçiniz.</span>
                                                @error('country_id')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <select class="form-select rounded-0 tom-select" name="state_id"
                                                    id="state_id">
                                                </select>
                                                <span class="form-hint small">Lütfen şehir seçiniz.</span>
                                                @error('state_id')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3 px-2">Seçili Plan Detayları</h5>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th class="text-dark">Plan Adı:</th>
                                                    <td>{{ $plan->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-dark">Periyot:</th>
                                                    <td>{{ $plan->periodicity_text }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-dark">Tutar:</th>
                                                    <td>{{ $plan->formatted_price }}</td>
                                                </tr>
                                                {{-- Vergi bilgileri için dinamik alan --}}
                                                <tbody id="taxRatesSection">
                                                    {{-- JavaScript ile burası dinamik olarak güncellenecek --}}
                                                </tbody>
                                                <tr>
                                                    <th class="text-dark">Toplam Tutar:</th>
                                                    <td id="totalAmount">{{ $plan->formatted_price }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <input type="hidden" name="plan_amount" value="{{ $plan->price }}">
                                <input type="hidden" id="basePlanPrice" value="{{ $plan->price }}">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3 px-2">Sözleşmeler</h5>
                                        <p class="small">Ödeme işlemini gerçekleştirmek için lütfen sözleşmeleri
                                            onaylayınız.
                                        </p>
                                        @if ($agreements->count() > 0)
                                            @foreach ($agreements as $agreement)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="agreements[{{ $agreement->id }}]"
                                                        id="agreement{{ $agreement->id }}" required>
                                                    <label class="form-check-label" for="agreement{{ $agreement->id }}">
                                                        {{ $agreement->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Ödeme Yöntemi</h5>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check payment-method">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                        id="payment_credit_card" value="credit_card">
                                                    <label
                                                        class="form-check-label payment-box d-flex align-items-start justify-content-start"
                                                        for="payment_credit_card">
                                                        <div class="payment-box-header mb-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="32"
                                                                height="32" fill="currentColor"
                                                                class="bi bi-credit-card" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
                                                                <path
                                                                    d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1z" />
                                                            </svg>
                                                        </div>
                                                        <div class="payment-box-title ms-2">
                                                            <h6 class="mb-0">Kredi Kartı</h6>
                                                            <small class="text-muted">PayTR ödeme sistemi üzerinden
                                                                ödemenizi güvenli bir şekilde yapabilirsiniz. Ödeme Yap
                                                                butonuna bastığınızda ödemeyi yapabilmeniz için
                                                                yönlendirileceksiniz.</small>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check payment-method">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                        id="payment_bank" value="bank">
                                                    <label
                                                        class="form-check-label payment-box d-flex align-items-start justify-content-start"
                                                        for="payment_bank">
                                                        <div class="payment-box-header mb-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="32"
                                                                height="32" fill="currentColor" class="bi bi-bank"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1zM2 6v7h1V6zm2 0v7h2.5V6zm3.5 0v7h1V6zm2 0v7H12V6zM13 6v7h1V6zm2-1V4H1v1zm-.39 9H1.39l-.25 1h13.72z" />
                                                            </svg>
                                                        </div>
                                                        <div class="payment-box-title ms-2">
                                                            <h6 class="mb-0">EFT/Havale</h6>
                                                            <small class="text-muted">Ödemenizi doğrudan banka hesabımıza
                                                                yapınız. Lütfen ilgili Sipariş Numarasını ödemenizin
                                                                açıklama kısmına yazınız. Ödemeniz onaylanmadıkça hesabınız
                                                                aktif olmayacaktır.</small>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-credit-card-2-front me-1" viewBox="0 0 20 20">
                                        <path
                                            d="M14 3a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zM2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z" />
                                        <path
                                            d="M2 5.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5" />
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

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ödeme butonu ve sözleşme kontrolü
            const submitButton = document.querySelector('button[type="submit"]');
            const agreementCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="agreements"]');

            // Başlangıçta butonu devre dışı bırak
            submitButton.disabled = true;

            // Sözleşmelerin durumunu kontrol eden fonksiyon
            function checkAgreements() {
                const allChecked = Array.from(agreementCheckboxes).every(checkbox => checkbox.checked);
                submitButton.disabled = !allChecked;
            }

            // Her checkbox değiştiğinde kontrol et
            agreementCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', checkAgreements);
            });

            // Ana JS dosyası TomSelect'leri initialize ettikten sonra çalışacak
            setTimeout(() => {
                // Tom-select instanceları al
                const countrySelect = document.getElementById('country_id').tomselect;
                const stateSelect = document.getElementById('state_id').tomselect;

                // Vergi hesaplama ve şehir yükleme fonksiyonları
                async function updateTaxInfo(countryId, stateId = null) {
                    try {
                        const url = stateId ?
                            `/app/taxes?country_id=${countryId}&state_id=${stateId}` :
                            `/app/taxes?country_id=${countryId}`;

                        const response = await fetch(url);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        const taxData = await response.json();

                        const taxRatesSection = document.getElementById('taxRatesSection');
                        const totalAmount = document.getElementById('totalAmount');
                        const basePrice = parseFloat(document.getElementById('basePlanPrice').value);

                        // Vergi tablosunu güncelle
                        if (taxData.taxes && taxData.taxes.length > 0) {
                            // Tüm vergileri listele
                            const taxHtml = taxData.taxes.map(tax => `
                <tr>
                    <th class="text-dark">${tax.code}:</th>
                    <td>%${tax.value}</td>
                </tr>
            `).join('');

                            taxRatesSection.innerHTML = taxHtml;

                            // Toplam tutarı hesapla
                            const totalWithTax = basePrice + (basePrice * taxData.tax_total / 100);
                            totalAmount.textContent = new Intl.NumberFormat('tr-TR', {
                                style: 'currency',
                                currency: 'TRY'
                            }).format(totalWithTax);
                        } else {
                            // Vergi yoksa temizle
                            taxRatesSection.innerHTML = '';
                            totalAmount.textContent = new Intl.NumberFormat('tr-TR', {
                                style: 'currency',
                                currency: 'TRY'
                            }).format(basePrice);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        // Hata durumunda UI'ı temizle
                        const taxRatesSection = document.getElementById('taxRatesSection');
                        const totalAmount = document.getElementById('totalAmount');
                        const basePrice = parseFloat(document.getElementById('basePlanPrice').value);

                        taxRatesSection.innerHTML = '';
                        totalAmount.textContent = new Intl.NumberFormat('tr-TR', {
                            style: 'currency',
                            currency: 'TRY'
                        }).format(basePrice);
                    }
                }

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
                        const response = await fetch(`/app/states?country_id=${countryId}`);
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

                // Event Listeners
                countrySelect.on('change', async function(value) {
                    await getStates(value);
                    await updateTaxInfo(value, null);
                });

                stateSelect.on('change', async function(value) {
                    const countryId = countrySelect.getValue();
                    await updateTaxInfo(countryId, value);
                });

                // Sayfa ilk yüklendiğinde
                const initialCountryId = countrySelect.getValue();
                if (initialCountryId) {
                    getStates(initialCountryId).then(() => {
                        const initialStateId = stateSelect.getValue();
                        updateTaxInfo(initialCountryId, initialStateId);
                    });
                }

            }, 100);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const selectedPaymentMethod = document.querySelector(
                    'input[name="payment_method"]:checked');
                if (!selectedPaymentMethod) {
                    e.preventDefault();
                    alert('Lütfen bir ödeme yöntemi seçiniz.');
                    return false;
                }
            });
        });
    </script>
@endsection
