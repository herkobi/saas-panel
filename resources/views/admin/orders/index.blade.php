@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Ödemeler',
    ])
    @include('admin.accounts.include.navigation')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.orders.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="row align-items-center mb-2">
                        <div class="col-lg-6">
                            <h2 class="mb-0">Son Ödemeler</h2>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th class="w-15">Durum</th>
                                    <th class="w-20">Ödeme Kodu</th>
                                    <th class="w-15">Hesap</th>
                                    <th class="w-20">Plan ve Tutar</th>
                                    <th class="w-15">Ödeme Tipi</th>
                                    <th class="w-15"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <span class="badge bg-success">Onaylandı</span>
                                        </td>
                                        <td>{{ $order->code }}</td>
                                        <td>{{ $order->tenant->code }}</td>
                                        <td>{{ $order->plan->name }} / {{ number_format($order->total_amount, 2) }}
                                            {{ $order->currency->code }}</td>
                                        <td>
                                            @if ($order->payment_type == 'bank')
                                                <span class="badge bg-info">Havale/EFT</span>
                                            @elseif($order->payment_type == 'credit_card')
                                                <span class="badge bg-success">Kredi Kartı</span>
                                            @else
                                                <span class="badge bg-secondary">Ücretsiz</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('panel.order.detail', $order->id) }}" class="btn btn-sm">
                                                Detay
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($orders->isEmpty())
                            <div class="text-center py-4">
                                <p class="text-muted">Henüz bir ödeme kaydı bulunmuyor.</p>
                            </div>
                        @endif
                    </div>

                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
