@extends('layouts.app')
@section('content')
    <div class="container h-100">
        @include('user.include.header', [
            'title' => 'Hesap Bilgileri',
        ])
        <div class="page-content flex-grow-1 d-flex flex-column shadow-sm">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="page-menu rounded-2">
                                @include('user.account.include.navigation')
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h2 class="border-bottom mb-4 pb-3">Faturalar</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
