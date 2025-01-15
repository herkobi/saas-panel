@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Araçlar',
    ])
    @include('admin.tools.include.navigation')
    <div class="page-content">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-3">
                    @include('admin.tools.config.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="row align-items-center mb-2">
                        <div class="col-lg-6">
                            <h2>{{ $country->name }} Ülkesindeki Eyalet/Şehirler</h2>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav justify-content-end">
                                <li class="nav-item">
                                    <a href="{{ route('panel.tools.config.state.create', $country->id) }}" class="btn"
                                        title="Yeni Şehir">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2">
                                            </path>
                                        </svg>
                                        Eyalet/Şehir Ekle
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
                                    <th class="w-40">Eyalet/Şehir Adı</th>
                                    <th class="w-20">Plaka Kodu</th>
                                    <th class="w-20">Telefon Kodu</th>
                                    <th class="w-10"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($states as $state)
                                    <tr>
                                        <td>
                                            @if ($state->status->value == 1)
                                                <span class="badge bg-success">{{ Status::title($state->status) }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ Status::title($state->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $state->name }}</td>
                                        <td>{{ $state->code }}</td>
                                        <td>{{ $state->phone }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('panel.tools.config.state.edit', $state->id) }}"
                                                class="btn btn-sm" title="Eyalet/Şehir Bilgileri">
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
