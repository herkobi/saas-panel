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
                            <h2 class="mb-0">Yazı Ekle</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('app.post.store') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="" class="form-label">Yazı Adı</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                        aria-describedby="helpId" placeholder="Yazı Adını Giriniz" value="" />
                                    <small id="helpId" class="form-text text-muted">Lütfen yazı adını giriniz</small>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Kaydet
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
