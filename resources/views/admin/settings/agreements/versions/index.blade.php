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
                            <h2>{{ $agreement->title }} İçerikleri</h2>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav justify-content-end">
                                <li class="nav-item">
                                    <a href="{{ route('panel.settings.agreement.version.create', $agreement->slug) }}"
                                        class="btn" title="Yeni Hesap">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2">
                                            </path>
                                        </svg>
                                        Versiyon Ekle
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
                                    <th class="w-30">Versiyon Numarası</th>
                                    <th class="w-30">Yayın Tarihi</th>
                                    <th class="w-20">İmzalar</th>
                                    <th class="w-10"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agreement->versions as $version)
                                    <tr>
                                        <td>
                                            @if ($version->status === AgreementVersionStatus::DRAFT)
                                                <span
                                                    class="badge bg-info text-dark">{{ AgreementVersionStatus::title($version->status) }}</span>
                                            @elseif ($version->status === AgreementVersionStatus::PUBLISHED)
                                                <span
                                                    class="badge bg-success">{{ AgreementVersionStatus::title($version->status) }}</span>
                                            @else
                                                <span
                                                    class="badge bg-dark">{{ AgreementVersionStatus::title($version->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $version->version }}</td>
                                        <td>{{ $version->status === AgreementVersionStatus::PUBLISHED ? $version->published_at->format('d.m.Y H:i') : '--' }}
                                        </td>
                                        <td></td>
                                        <td class="text-center">
                                            @if ($version->status === AgreementVersionStatus::DRAFT)
                                                <a href="{{ route('panel.settings.agreement.version.edit', [$agreement->slug, $version->id]) }}"
                                                    class="btn btn-sm" title="Düzenle">
                                                    <i class="bi bi-pencil"></i> Düzenle
                                                </a>
                                            @else
                                                <a href="{{ route('panel.settings.agreement.version.show', [$agreement->slug, $version->id]) }}"
                                                    class="btn btn-sm" title="Görüntüle">
                                                    <i class="bi bi-eye"></i> Görüntüle
                                                </a>
                                            @endif
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
