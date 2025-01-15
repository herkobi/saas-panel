@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Müşteriler',
    ])
    @include('admin.accounts.include.navigation')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.accounts.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="row align-items-center mb-2">
                        <div class="col-lg-6">
                            <h2 class="mb-0">Müşteri Grupları</h2>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav justify-content-end">
                                <li class="nav-item">
                                    <a href="{{ route('panel.accountgroup.create') }}" class="btn" title="Yeni Grup">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                        </svg>
                                        Yeni Grup Ekle
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="w-35">Müşteri Grubu</th>
                                    <th class="w-25">Renk</th>
                                    <th class="w-15">Müşteriler</th>
                                    <th class="w-15">Müşteri Sayısı</th>
                                    <th class="w-10"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groups as $group)
                                    <tr>
                                        <td>{{ $group->title }}</td>
                                        <td>{{ $group->color }}</td>
                                        <td>
                                            <a href="{{ route('panel.accountgroup.detail', $group->id) }}" class="link"
                                                title="Müşteriler">
                                                Müşteriler
                                            </a>
                                        </td>
                                        <td>{{ $group->getUsersCountAttribute() }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('panel.accountgroup.edit', $group->id) }}" class="btn btn-sm"
                                                title="Grup Bilgileri">
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
