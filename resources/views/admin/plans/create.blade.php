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
                    <form action="{{ route('panel.settings.page.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3 row align-items-center">
                                <label class="col-lg-2 col-md-3 col-form-label required">Plan Döngüsü</label>
                                <div class="col-lg-10 col-md-9">
                                    <div>
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input mountly_yearly" type="radio" name="interval"
                                                value="Monthly|Yearly" checked>
                                            <span class="form-check-label">Aylık ve Yıllık</span>
                                        </label>
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input mountly" type="radio" name="interval"
                                                value="Monthly">
                                            <span class="form-check-label">Sadece Aylık</span>
                                        </label>
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input yearly" type="radio" name="interval"
                                                value="Yearly">
                                            <span class="form-check-label">Sadece Yıllık</span>
                                        </label>
                                    </div>
                                    @error('montly')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-lg-2 col-md-3 col-form-label required">Plan Ücreti</div>
                                <div class="col-lg-10 col-md-9">
                                    <div class="row">
                                        <div id="montlyPrice" class="col-lg-3 d-none">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0 pe-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-cash">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                                        <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                        <path
                                                            d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" />
                                                    </svg>
                                                </span>
                                                <input type="text" name="price_montly"
                                                    class="form-control border-start-0 @error('title') is-invalid @enderror"
                                                    value="{{ old('price_montly') }}" placeholder="Aylık Plan Ücreti">
                                            </div>
                                            <small class="form-hint">Lütfen aylık plan ücretini giriniz.</small>
                                            @error('price_montly')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div id="yearlyPrice" class="col-lg-3 d-none">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0 pe-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-cash">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                                                        <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                        <path
                                                            d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" />
                                                    </svg>
                                                </span>
                                                <input type="text" name="price_yearly"
                                                    class="form-control border-start-0 @error('title') is-invalid @enderror"
                                                    value="{{ old('price_yearly') }}" placeholder="Yıllık Plan Ücreti">
                                            </div>
                                            <small class="form-hint">Lütfen yıllık plan ücretini giriniz.</small>
                                            @error('price_yearly')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-md-3 col-form-label required">Plan Adı</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" placeholder="Plan Adı" required>
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
                                        value="{{ old('desc') }}" placeholder="Plan Açıklaması" required>
                                    <small class="form-hint">Lütfen plan ile ilgili kısa açıklama giriniz.</small>
                                    @error('desc')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-lg-6 mb-1">
                                    <div class="row">
                                        <label class="col-lg-4 col-form-label required">Plan Kodu</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="code"
                                                class="form-control @error('code') is-invalid @enderror"
                                                value="{{ old('code') }}" placeholder="Plan Kodu">
                                            <small class="form-hint">Lütfen planı tanımlayacak bir değer giriniz. Boş
                                                bırakırsanız plan adından otomatik olarak oluşturulur.</small>
                                            @error('code')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-1">
                                    <div class="row">
                                        <label class="col-lg-4 col-form-label required">Deneme Süresi</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="trialDays"
                                                class="form-control @error('code') is-invalid @enderror"
                                                value="{{ old('trialDays') }}" placeholder="Deneme Süresi" required>
                                            <small class="form-hint">Ödeme yapılmadan önce deneme süresi olacaksa ilgili
                                                süreyi giriniz. Girdiğiniz değer gün olarak değerlendirilir.</small>
                                            @error('trialDays')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
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
@endsection

@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get references to the radio buttons and price inputs
            const mountlyYearly = document.querySelector('.mountly_yearly');
            const mountly = document.querySelector('.mountly');
            const yearly = document.querySelector('.yearly');
            const mountlyPrice = document.getElementById('montlyPrice');
            const yearlyPrice = document.getElementById('yearlyPrice');

            // Function to show/hide price inputs based on selected radio button
            function togglePriceInputs() {
                if (mountlyYearly.checked) {
                    // Show both price inputs when "Aylık ve Yıllık" is selected
                    mountlyPrice.classList.remove('d-none');
                    yearlyPrice.classList.remove('d-none');
                } else if (mountly.checked) {
                    // Show only monthly price input when "Sadece Aylık" is selected
                    mountlyPrice.classList.remove('d-none');
                    yearlyPrice.classList.add('d-none');
                } else if (yearly.checked) {
                    // Show only yearly price input when "Sadece Yıllık" is selected
                    mountlyPrice.classList.add('d-none');
                    yearlyPrice.classList.remove('d-none');
                }
            }

            // Add event listeners to each radio button to detect changes
            mountlyYearly.addEventListener('change', togglePriceInputs);
            mountly.addEventListener('change', togglePriceInputs);
            yearly.addEventListener('change', togglePriceInputs);

            // Call the function on page load to set the initial state
            togglePriceInputs();
        });
    </script>
@endsection
