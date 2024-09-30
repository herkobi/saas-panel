@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Planlar',
    ])
    <div class="page-content flex-grow-1 d-flex flex-column shadow-sm">
        <div class="row flex-grow-1">
            <div class="col-20 col-lg-3 col-md-3">
                <div class="page-menu rounded-2">
                    @include('admin.plans.include.navigation')
                </div>
            </div>
            <div class="col-80 col-lg-9 col-md-9">
                <div class="card h-100 border-0 mb-5">
                    <div class="card-header border-0 bg-white p-0 mb-3">
                        <div class="d-flex align-items-center justify-content-between w-100 border-bottom pb-2">
                            <h1 class="card-title">Özellikler</h1>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th class="w-20">Özellik Adı</th>
                                    <th class="w-20">Kullanımı Takip Et</th>
                                    <th class="w-20">Kotayı Takip Et</th>
                                    <th class="w-20">Kullandıkça Öde</th>
                                    <th class="w-20"></th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
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
                                                        ? 'Gün'
                                                        : ($feature->periodicity_type == 'PeriodicityType::Week'
                                                            ? 'Hafta'
                                                            : ($feature->periodicity_type == 'PeriodicityType::Month'
                                                                ? 'Ay'
                                                                : 'Yıl')) }}
                                                </div>
                                            @else
                                                <div class="d-block">Hayır</div>
                                            @endif
                                        </td>
                                        <td>{{ $feature->quota == true ? 'Evet' : 'Hayır' }}</td>
                                        <td>{{ $feature->postpaid == true ? 'Evet' : 'Hayır' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('panel.feature.edit', $feature->id) }}"
                                                class="btn btn-outline-primary btn-sm">
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
