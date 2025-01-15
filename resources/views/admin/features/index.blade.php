@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Planlar',
    ])
    @include('admin.plans.include.navigation')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.plans.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="row align-items-center mb-2">
                        <div class="col-lg-6">
                            <h2 class="mb-0">Özellikler</h2>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav justify-content-end">
                                <li class="nav-item">
                                    <a href="{{ route('panel.feature.create') }}" class="btn" title="Yeni Özellik">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2">
                                            </path>
                                        </svg>
                                        Özellik Ekle
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th class="w-20">Özellik Adı</th>
                                    <th class="w-20">Kullanımı Takip Et</th>
                                    <th class="w-20">Dosya Kotası Tanımlı</th>
                                    <th class="w-20">Kullandıkça Öde</th>
                                    <th class="w-20"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($features as $feature)
                                    <tr>
                                        <td>{{ $feature->name }}</td>
                                        <td>
                                            @if ($feature->consumable == true)
                                                <div class="d-block">Evet</div>
                                                <div class="d-block">
                                                    <span class="fw-medium">Yenilenme Süresi:</span>
                                                    {{ $feature->periodicity }}
                                                    {{ $feature->periodicity_type == 'PeriodicityType::Day'
                                                        ? ' / Gün'
                                                        : ($feature->periodicity_type == 'PeriodicityType::Week'
                                                            ? ' / Hafta'
                                                            : ($feature->periodicity_type == 'PeriodicityType::Month'
                                                                ? ' / Ay'
                                                                : ' / Yıl')) }}
                                                </div>
                                            @else
                                                <div class="d-block">Hayır</div>
                                            @endif
                                        </td>
                                        <td>{{ $feature->quota == true ? 'Evet' : 'Hayır' }}</td>
                                        <td>{{ $feature->postpaid == true ? 'Evet' : 'Hayır' }}</td>
                                        <td class="text-center">
                                            @if ($feature->deleted_at)
                                                <div class="btn-group">
                                                    <form action="{{ route('panel.feature.restore', $feature->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm">
                                                            Geri Getir
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('panel.feature.force-delete', $feature->id) }}"
                                                        method="POST" class="d-inline ms-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm"
                                                            onclick="return confirm('Bu planı kalıcı olarak silmek istediğinize emin misiniz?')">
                                                            Tamamen Sil
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <a href="{{ route('panel.feature.edit', $feature->id) }}"
                                                    class="btn btn-sm">
                                                    Düzenle
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
