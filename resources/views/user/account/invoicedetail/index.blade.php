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
                                <h2>Fatura Bilgileri</h2>
                                <a href="{{ route('app.account.invoicedetail.create') }}" class="btn btn-sm btn-primary"
                                    title="Fatura Bilgisi Ekle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-plus m-n4">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Yeni Ekle
                                </a>
                            </div>
                            <div class="row">
                                @foreach ($invoicedetails as $inv)
                                    <div class="col-md-4 mb-3">
                                        <div class="card card-sm">
                                            <div class="card-header bg-white">
                                                <h3 class="card-title mb-0">{{ $inv->title }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex align-items-start justify-content-start mb-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
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
                                                    <span class="ms-2">{{ $inv->invoiceName }}</span>
                                                </div>
                                                <div class="d-flex align-items-start justify-content-start mb-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-map-2">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                                                        <path d="M9 4v13" />
                                                        <path d="M15 7v5.5" />
                                                        <path
                                                            d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" />
                                                        <path d="M19 18v.01" />
                                                    </svg>
                                                    <span
                                                        class="ms-2">{{ $inv->address }}<br>{{ !empty($inv->zipCode) ? $inv->zipCode : '' }}<br>{{ $inv->state . ' / ' . $inv->city }}</span>
                                                </div>
                                                <div class="d-flex align-items-start justify-content-start mb-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-number">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 17v-10l7 10v-10" />
                                                        <path d="M15 17h5" />
                                                        <path d="M17.5 10m-2.5 0a2.5 3 0 1 0 5 0a2.5 3 0 1 0 -5 0" />
                                                    </svg>
                                                    <span class="ms-2">{{ $inv->taxNumber }}</span>
                                                </div>
                                                <div class="d-flex align-items-start justify-content-start mb-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-building">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 21l18 0" />
                                                        <path d="M9 8l1 0" />
                                                        <path d="M9 12l1 0" />
                                                        <path d="M9 16l1 0" />
                                                        <path d="M14 8l1 0" />
                                                        <path d="M14 12l1 0" />
                                                        <path d="M14 16l1 0" />
                                                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                                                    </svg>
                                                    <span class="ms-2">{{ $inv->taxOffice }}</span>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ route('app.account.invoicedetail.edit', $inv->id) }}"
                                                    class="btn btn-sm btn-primary text-white">Düzenle</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
