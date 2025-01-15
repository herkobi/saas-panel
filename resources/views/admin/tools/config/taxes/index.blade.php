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
                            <h2>Vergi Oranları</h2>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav justify-content-end">
                                <li class="nav-item">
                                    <a href="{{ route('panel.tools.config.tax.create') }}" class="btn"
                                        title="Yeni Vergi Oranı">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2">
                                            </path>
                                        </svg>
                                        Vergi Oranı Ekle
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
                                    <th class="w-25">Vergi Oranı Adı</th>
                                    <th class="w-25">Vergi Oranı Bilgileri</th>
                                    <th class="w-30">Bölgeler</th>
                                    <th class="w-10"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taxes as $tax)
                                    <tr>
                                        <td>
                                            @if ($tax->status->value == 1)
                                                <span class="badge bg-success">{{ Status::title($tax->status) }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ Status::title($tax->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $tax->title }}</td>
                                        <td>{{ $tax->code . ' / %' . $tax->value }}</td>
                                        <td>
                                            @foreach ($tax->regions as $region)
                                                {{ $region->country->name }}
                                                @if ($region->state)
                                                    - {{ $region->state->name }}
                                                @endif
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('panel.tools.config.tax.edit', $tax->id) }}"
                                                class="btn btn-sm" title="Vergi Oranı Bilgileri">
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
