@extends('layouts.panel')
@section('content')
   @include('admin.include.header', [
       'title' => 'Ödemeler',
   ])
   <!--@include('admin.orders.include.navigation')-->
   <div class="page-content">
       <div class="container">
           <div class="row">
               <div class="col-lg-3">
                   @include('admin.orders.include.sidebar')
               </div>
               <div class="col-lg-9">
                   <div class="row align-items-center mb-2">
                       <div class="col-lg-6">
                           <h2 class="mb-0">Tüm Ödemeler</h2>
                       </div>
                       <div class="col-lg-6">
                           <ul class="nav justify-content-end">
                               <li class="nav-item">
                                   <a href="{{ route('panel.orders.pending') }}" class="btn" title="Bekleyen Ödemeler">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-hourglass-split" viewBox="0 0 16 16">
                                           <path d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48zm1 0v3.96c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z"/>
                                       </svg>
                                       Bekleyen Ödemeler
                                   </a>
                               </li>
                           </ul>
                       </div>
                   </div>
                   <div class="table-responsive">
                       <table class="table">
                           <thead class="table-light">
                               <tr>
                                   <th class="w-15">Kod</th>
                                   <th class="w-15">Hesap</th>
                                   <th class="w-20">Plan</th>
                                   <th class="w-15">Tutar</th>
                                   <th class="w-15">Ödeme Tipi</th>
                                   <th class="w-10">Durum</th>
                                   <th class="w-10"></th>
                               </tr>
                           </thead>
                           <tbody>
                               @foreach ($orders as $order)
                                   <tr>
                                       <td>{{ $order->code }}</td>
                                       <td>{{ $order->tenant->code }}</td>
                                       <td>{{ $order->plan->name }}</td>
                                       <td>{{ number_format($order->total_amount, 2) }} {{ $order->currency->code }}</td>
                                       <td>
                                           @if($order->payment_type == 'bank')
                                               <span class="badge bg-info">Havale/EFT</span>
                                           @elseif($order->payment_type == 'credit_card')
                                               <span class="badge bg-success">Kredi Kartı</span>
                                           @else
                                               <span class="badge bg-secondary">Ücretsiz</span>
                                           @endif
                                       </td>
                                       <td>
                                           @if($order->orderstatus->code == 'PENDING_PAYMENT')
                                               <span class="badge bg-warning">Ödeme Bekliyor</span>
                                           @elseif($order->orderstatus->code == 'REVIEW')
                                               <span class="badge bg-info">İnceleniyor</span>
                                           @elseif($order->orderstatus->code == 'APPROVED')
                                               <span class="badge bg-success">Onaylandı</span>
                                           @elseif($order->orderstatus->code == 'REJECTED')
                                               <span class="badge bg-danger">Reddedildi</span>
                                           @endif
                                       </td>
                                       <td class="text-center">
                                           <a href="{{ route('panel.orders.show', $order->code) }}" class="btn btn-sm">
                                               Detay
                                           </a>
                                       </td>
                                   </tr>
                               @endforeach
                           </tbody>
                       </table>

                       @if($orders->isEmpty())
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
