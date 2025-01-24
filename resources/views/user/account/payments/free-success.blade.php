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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h5 class="alert-heading">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                        class="bi bi-info-circle me-2" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                        <path
                                            d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                    </svg>
                                    Hesap Aktivasyonu.
                                </h5>
                                <p>Hesabınız etkinleştirilmiştir. Ücretsiz planınızı kullanmaya başlayabilirsiniz. Keyifli
                                    çalışmalar dileriz!</p>
                            </div>
                        </div>
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
                                            <th>Toplam Tutar:</th>
                                            <td>{{ number_format($order->total_amount, 2) }} {{ $order->currency->symbol }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
