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
                            <h2 class="mb-0">Ana Planlar</h2>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav justify-content-end">
                                <li class="nav-item">
                                    <a href="{{ route('panel.plan.create') }}" class="btn" title="Yeni Plan">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2">
                                            </path>
                                        </svg>
                                        Plan Ekle
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive mb-5">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th class="w-20">Plan Adı</th>
                                    <th class="w-20">Plan Özellikleri</th>
                                    <th class="w-15">Plan Döngüsü</th>
                                    <th class="w-15">Plan Ücreti</th>
                                    <th class="w-15">Deneme Süresi</th>
                                    <th class="w-15"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $plan)
                                    <tr>
                                        <td>
                                            {{ $plan->name }}
                                            @if ($plan->deleted_at)
                                                <span class="badge bg-danger ms-2">Silinmiş</span>
                                            @endif
                                        </td>
                                        <td>
                                            <ul class="ps-3">
                                                @foreach ($plan->features as $feature)
                                                    <li>{{ $feature->name }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $plan->periodicity }}
                                            {{ $plan->periodicity_type == 'PeriodicityType::Day'
                                                ? 'Gün'
                                                : ($plan->periodicity_type == 'PeriodicityType::Week'
                                                    ? 'Hafta'
                                                    : ($plan->periodicity_type == 'PeriodicityType::Month'
                                                        ? 'Ay'
                                                        : ($plan->periodicity_type == 'PeriodicityType::Year'
                                                            ? 'Yıl'
                                                            : ''))) }}
                                        </td>
                                        <td>{{ $plan->formatted_price }}</td>
                                        <td>{{ $plan->grace_days }} gün</td>
                                        <td class="text-center">
                                            @if ($plan->deleted_at)
                                                <div class="btn-group">
                                                    <form action="{{ route('panel.plan.restore', $plan->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm">
                                                            Geri Getir
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('panel.plan.force-delete', $plan->id) }}"
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
                                                <a href="{{ route('panel.plan.edit', $plan->id) }}" class="btn btn-sm">
                                                    Düzenle
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($tenants)
                        <div class="row align-items-center mb-2">
                            <div class="col-lg-6">
                                <h2 class="mb-0">Hesaba Özel Planlar</h2>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="w-15">Hesap Kodu</th>
                                        <th class="w-20">Plan Adı</th>
                                        <th class="w-20">Plan Özellikleri</th>
                                        <th class="w-15">Plan Döngüsü</th>
                                        <th class="w-15">Plan Ücreti</th>
                                        <th class="w-15">Deneme Süresi</th>
                                        <th class="w-15"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tenants as $plan)
                                        <tr>
                                            <td>{{ $plan->tenant->code }}</td>
                                            <td>
                                                {{ $plan->name }}
                                                @if ($plan->deleted_at)
                                                    <span class="badge bg-danger ms-2">Silinmiş</span>
                                                @endif
                                            </td>
                                            <td>
                                                <ul class="ps-3">
                                                    @foreach ($plan->features as $feature)
                                                        <li>{{ $feature->name }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>{{ $plan->periodicity }}
                                                {{ $plan->periodicity_type == 'PeriodicityType::Day'
                                                    ? 'Gün'
                                                    : ($plan->periodicity_type == 'PeriodicityType::Week'
                                                        ? 'Hafta'
                                                        : ($plan->periodicity_type == 'PeriodicityType::Month'
                                                            ? 'Ay'
                                                            : ($plan->periodicity_type == 'PeriodicityType::Year'
                                                                ? 'Yıl'
                                                                : ''))) }}
                                            </td>
                                            <td>{{ $plan->formatted_price }}</td>
                                            <td>{{ $plan->grace_days }} gün</td>
                                            <td class="text-center">
                                                @if ($plan->deleted_at)
                                                    <div class="btn-group">
                                                        <form action="{{ route('panel.plan.restore', $plan->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm">
                                                                Geri Getir
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('panel.plan.force-delete', $plan->id) }}"
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
                                                    <a href="{{ route('panel.plan.edit', $plan->id) }}"
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
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
