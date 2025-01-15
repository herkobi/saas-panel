@extends('layouts.app')
@section('content')
    @include('user.include.header', [
        'title' => 'Ödeme Bilgileri',
    ])
    @include('user.account.include.navigation')

    <div class="page-content mt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-info-circle me-2" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path
                                    d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                            </svg>
                            Ödeme Talimatları
                        </h5>
                        <p>Lütfen havale/EFT işleminizde açıklama kısmına sipariş numaranızı
                            (<strong>{{ $order->code }}</strong>) yazmayı unutmayınız.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Sipariş Özeti</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Sipariş No:</th>
                                            <td>{{ $order->code }}</td>
                                        </tr>
                                        <tr>
                                            <th>Plan:</th>
                                            <td>{{ $order->plan->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tutar:</th>
                                            <td>{{ number_format($order->amount, 2) }} {{ $order->currency->code }}</td>
                                        </tr>
                                        @if (isset($order->invoice_data['tax_data']))
                                            @foreach ($order->invoice_data['tax_data'] as $tax)
                                                <tr>
                                                    <th>{{ $tax['code'] }}:</th>
                                                    <td>{{ number_format($tax['amount'], 2) }} {{ $order->currency->code }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <th>Toplam:</th>
                                            <td>{{ number_format($order->total_amount, 2) }} {{ $order->currency->code }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Banka Hesap Bilgileri</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Banka</th>
                                                    <th>IBAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bacs as $bac)
                                                    <tr>
                                                        <td>{{ $bac->bank_name }}</td>
                                                        <td class="text-monospace">{{ $bac->iban }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ödeme Bildirimi Formu -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ödeme Bildirimi</h5>
                            <form action="{{ route('app.account.payment.upload', $order->code) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="document" class="form-label">Dekont</label>
                                    <input type="file" class="form-control" id="document" name="document"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <div class="form-text">Maksimum dosya boyutu: 2MB. İzin verilen formatlar: PDF, JPG, PNG
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-upload me-1" viewBox="0 0 16 16">
                                        <path
                                            d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                        <path
                                            d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                                    </svg>
                                    Dekont Yükle
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
