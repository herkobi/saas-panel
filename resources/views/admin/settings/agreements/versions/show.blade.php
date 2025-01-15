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
                    <h3 class="form-title border-bottom mb-3 pb-3">
                        {{ $agreementVersion->agreement->title . ' ' . $agreementVersion->version }} İçeriği</h3>
                    <div class="content">
                        {{ $agreementVersion->content }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
