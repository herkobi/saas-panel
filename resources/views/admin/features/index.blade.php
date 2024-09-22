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
                                    <th class="w-60">Açıklama</th>
                                    <th class="w-20"></th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @foreach ($features as $feature)
                                    <tr>
                                        <td>{{ $feature->title }}</td>
                                        <td>{{ $feature->desc }}</td>
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