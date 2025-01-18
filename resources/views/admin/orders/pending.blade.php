@extends('layouts.panel')
@section('content')
   @include('admin.include.header', [
       'title' => 'Bekleyen Ödemeler',
   ])
   <div class="page-content">
       <div class="container">
           <div class="row">
               <div class="col-lg-12">
                   <div class="row align-items-center mb-2">
                       <div class="col-lg-6">
                           <h2 class="mb-0">Bekleyen Ödemeler</h2>
                       </div>
                   </div>

                   <div class="table-responsive">
                       <table class="table">
                           <thead class="table-light">
                               <tr>
                                   <th class="w-15">Kod</th>
                                   <th class="w-15">Hesap</th>
                                   <th class="w-15">Plan</th>
                                   <th class="w-15">Tutar</th>
                                   <th class="w-15">Durum</th>
                                   <th class="w-10">Dekont</th>
                                   <th class="w-15"></th>
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
                                           @if($order->orderstatus->code == 'PENDING_PAYMENT')
                                               <span class="badge bg-warning">Ödeme Bekliyor</span>
                                           @elseif($order->orderstatus->code == 'REVIEW')
                                               <span class="badge bg-info">İnceleniyor</span>
                                           @endif
                                       </td>
                                       <td>
                                           @if($order->document)
                                               @if(pathinfo($order->document, PATHINFO_EXTENSION) === 'pdf')
                                                   <a href="{{ Storage::url($order->document) }}" class="btn btn-sm" target="_blank">
                                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-pdf" viewBox="0 0 16 16">
                                                           <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1"/>
                                                           <path d="M4.603 12.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 19.4 19.4 0 0 0 1.062-2.227 7.2 7.2 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a10.9 10.9 0 0 0 .98 1.686 5.7 5.7 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.85.85 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.6 11.6 0 0 0-1.997.406 11.3 11.3 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.79.79 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.2 8.2 0 0 0 .45-.606zm1.64-1.33a12.7 12.7 0 0 1 1.01-.193 11.7 11.7 0 0 1-.51-.858 20.7 20.7 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.9 3.9 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.61.61 0 0 0-.032-.198.52.52 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/>
                                                       </svg>
                                                       Görüntüle
                                                   </a>
                                               @else
                                                   <a href="{{ Storage::url($order->document) }}" class="btn btn-sm" target="_blank">
                                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                                           <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                           <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/>
                                                       </svg>
                                                       Görüntüle
                                                   </a>
                                               @endif
                                           @else
                                               <span class="text-muted">Yok</span>
                                           @endif
                                       </td>
                                       <td>
                                           <div class="btn-group">
                                               <a href="{{ route('panel.orders.show', $order->code) }}" class="btn btn-sm">
                                                   Detay
                                               </a>
                                               @if($order->document)
                                                   <form action="{{ route('panel.orders.approve', $order->code) }}" method="POST" class="d-inline ms-1">
                                                       @csrf
                                                       <button type="submit" class="btn btn-sm btn-success">
                                                           Onayla
                                                       </button>
                                                   </form>
                                                   <form action="{{ route('panel.orders.reject', $order->code) }}" method="POST" class="d-inline ms-1">
                                                       @csrf
                                                       <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Ödemeyi reddetmek istediğinize emin misiniz?')">
                                                           Reddet
                                                       </button>
                                                   </form>
                                               @endif
                                           </div>
                                       </td>
                                   </tr>
                               @endforeach
                           </tbody>
                       </table>

                       @if($orders->isEmpty())
                           <div class="text-center py-4">
                               <p class="text-muted">Bekleyen ödeme bulunmuyor.</p>
                           </div>
                       @endif
                   </div>

                   {{ $orders->links() }}
               </div>
           </div>
       </div>
   </div>
@endsection
