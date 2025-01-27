@extends('layouts.app')
@section('content')
    @include('user.include.header', [
        'title' => 'Yazılar',
    ])
    @include('user.posts.include.navigation')
    <div class="page-content">
        <div class="container">
            <div class="row my-5">
                <div class="col-md-8 offset-md-2">
                    <div class="row align-items-center mb-2">
                        <div class="col-lg-6">
                            <h2 class="mb-0">Yazı Düzenle</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
