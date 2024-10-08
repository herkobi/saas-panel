@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Ayarlar',
    ])
    <div class="page-content flex-grow-1 d-flex flex-column shadow-sm">
        <div class="row flex-grow-1">
            <div class="col-20 col-lg-3 col-md-3">
                <div class="page-menu rounded-2 mb-3">
                    @include('admin.accounts.include.navigation')
                </div>
                <div class="account-menu">
                    @include('admin.accounts.include.account-navigation')
                </div>
            </div>
            <div class="col-80 col-lg-9 col-md-9">
                <div class="card h-100 border-0 mb-5">
                    <div class="card-header border-0 bg-white p-0 mb-3">
                        <div class="d-flex align-items-center justify-content-between w-100 border-bottom pb-2">
                            <h1 class="card-title">Hesap Bilgileri</h1>
                            <a href="{{ route('panel.account.create') }}" class="btn btn-primary btn-sm rounded-sm">Yeni
                                Kişi Ekle</a>
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <div class="row">
                            <div class="col-lg-4">
                                @include('admin.accounts.include.profile-card')
                                @include('admin.accounts.include.profile-detail')
                            </div>
                            <div class="col-lg-8">
                                <div class="profile-content">
                                    <div class="card">
                                        <div class="card-header bg-white">
                                            <ul class="nav nav-pills card-header-pills">
                                                <li class="nav-item">
                                                    <a class="nav-link active"
                                                        href="{{ route('panel.account.detail', $user->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon nav-link-icon">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M3 12h4l3 8l4 -16l3 8h4" />
                                                        </svg>
                                                        Kullanıcı İşlemleri
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"
                                                        href="{{ route('panel.account.authlogs', $user->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon nav-link-icon">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M10 9a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                            <path d="M4 8v-2a2 2 0 0 1 2 -2h2" />
                                                            <path d="M4 16v2a2 2 0 0 0 2 2h2" />
                                                            <path d="M16 4h2a2 2 0 0 1 2 2v2" />
                                                            <path d="M16 20h2a2 2 0 0 0 2 -2v-2" />
                                                            <path d="M8 16a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2" />
                                                        </svg>
                                                        Oturum Kayıtları
                                                    </a>
                                                </li>
                                                <li class="nav-item ms-auto">
                                                    @include('admin.accounts.include.dropdown')
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            @foreach ($user->activities as $activity)
                                                <div class="border-bottom pb-2 mb-2">
                                                    <div class="row">
                                                        <div class="col-lg-3">{{ $activity->created_at }}</div>
                                                        <div class="col-lg-9">
                                                            <h4>{{ $activity->message }}</h4>
                                                            <span class="text-secondary">
                                                                {{ $activity->log }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.accounts.include.modals')
@endsection
