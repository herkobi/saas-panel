@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Ayarlar',
    ])
    @include('admin.settings.include.navigation')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.settings.agreements.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="row align-items-center mb-2">
                        <div class="col-lg-6">
                            <h2>Tüm İmzalar</h2>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Sözleşme Adı</th>
                                    <th>Versiyon Numarası</th>
                                    <th>Kullanıcı Ad Soyad</th>
                                    <th>Kullanıcı E-posta Adresi</th>
                                    <th>İşlem Tarihi</th>
                                    <th>IP Adresi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($signatures as $signature)
                                    <tr>
                                        <td>{{ $signature->agreement->title }}</td>
                                        <td>{{ $signature->version->version }}</td>
                                        <td>{{ $signature->user->name }} {{ $signature->user->surname }}</td>
                                        <td>{{ $signature->user->email }}</td>
                                        <td>{{ $signature->accepted_at }}</td>
                                        <td>{{ $signature->ip_address }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $signatures->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
