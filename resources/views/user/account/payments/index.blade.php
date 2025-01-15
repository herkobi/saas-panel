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
                            <h3 class="form-title border-bottom mb-3 pb-3">Ödemeler</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sipariş Kodu</th>
                                            <th>Tarih</th>
                                            <th>Tutar</th>
                                            <th>Ödeme Yöntemi</th>
                                            <th>Durum</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $order)
                                            <tr>
                                                <td>{{ $order->code }}</td>
                                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                                <td>{{ $order->formatted_amount }}</td>
                                                <td>{{ $order->payment_type == 'bank' ? 'Banka Havalesi' : 'Kredi Kartı' }}
                                                </td>
                                                <td>{{ $order->orderstatus->title }}</td>
                                                <td>
                                                    @if ($order->orderstatus->code === 'PENDING_PAYMENT')
                                                        <a href="{{ route('app.account.payment.bacs-success', $order->code) }}"
                                                            class="btn btn-sm">
                                                            Ödeme Bilgileri
                                                        </a>
                                                    @else
                                                        <a href="{{ route('app.account.payment.show', $order->code) }}"
                                                            class="btn btn-sm btn-primary">
                                                            Detay
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Henüz bir ödeme bulunmamaktadır.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
