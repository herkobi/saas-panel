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
                            <h2>Sözleşmeler</h2>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav justify-content-end">
                                <li class="nav-item">
                                    <a href="{{ route('panel.settings.agreement.admin.create') }}" class="btn"
                                        title="Yeni Hesap">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2">
                                            </path>
                                        </svg>
                                        Sözleşme Ekle
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="w-10">Durum</th>
                                    <th class="w-30">Sözleşme</th>
                                    <th class="w-30">İçerikler</th>
                                    <th class="w-20">İmzalar</th>
                                    <th class="w-10"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agreements as $agreement)
                                    <tr>
                                        <td>
                                            @if ($agreement->status->value == 1)
                                                <span
                                                    class="badge bg-success">{{ Status::title($agreement->status) }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ Status::title($agreement->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $agreement->title }}</td>
                                        <td><a href="{{ route('panel.settings.agreement.version.detail', $agreement->slug) }}"
                                                title="İçerikler">İçerikler</a></td>
                                        <td><a href="{{ route('panel.settings.agreement.agreementSignatures', $agreement->id) }}"
                                                title="İmzalar">İmzalar</a></td>
                                        <td class="text-center">
                                            <a href="{{ route('panel.settings.agreement.admin.edit', $agreement->id) }}"
                                                class="btn btn-sm" title="Hesap Bilgileri">
                                                Düzenle
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
