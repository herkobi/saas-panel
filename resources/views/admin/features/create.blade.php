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
                            <h1 class="card-title">Yeni Özellik Ekle</h1>
                        </div>
                    </div>
                    <form action="{{ route('panel.feature.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-md-3 col-form-label required">Özellik Adı</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" placeholder="Özellik Adı" required>
                                    <small class="form-hint">Lütfen özellik adını giriniz.</small>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-md-3 col-form-label required">Özellik Açıklaması</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" name="desc"
                                        class="form-control @error('desc') is-invalid @enderror" value="{{ old('desc') }}"
                                        placeholder="Özellik Açıklaması" required>
                                    <small class="form-hint">Lütfen özellik ile ilgili kısa açıklama giriniz.</small>
                                    @error('desc')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-lg-10 col-md-9 offset-lg-2 offset-md-3">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 12l5 5l10 -10" />
                                            <path d="M2 12l5 5m5 -5l5 -5" />
                                        </svg>
                                        Özellik Ekle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form action="{{ route('panel.feature.store') }}" method="POST">
                        @csrf
                        <div class="container mt-4">
                            <h2>Feature Form</h2>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="quota" class="form-label">Quota</label>
                                    <input type="number" class="form-control" id="quota" name="quota">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="periodicity_type" class="form-label">Periodicity Type</label>
                                    <select class="form-select" id="periodicity_type" name="periodicity_type">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month">Month</option>
                                        <option value="year">Year</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="periodicity" class="form-label">Periodicity</label>
                                    <input type="number" class="form-control" id="periodicity" name="periodicity">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="consumable" name="consumable">
                                        <label class="form-check-label" for="consumable">
                                            Consumable
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="postpaid" name="postpaid">
                                        <label class="form-check-label" for="postpaid">
                                            Postpaid
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
