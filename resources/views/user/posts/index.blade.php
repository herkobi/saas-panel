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
                    @if (!$hasFeature)
                        <div class="feature-overlay">
                            <div class="alert alert-warning text-center">
                                <h5>İçerik oluşturma özelliği planınızda bulunmuyor</h5>
                                <p>Detaylı bilgi için <a href="{{ route('app.account.plans') }}">planları
                                        inceleyebilirsiniz</a></p>
                            </div>
                        </div>
                    @else
                        <div class="row align-items-center mb-2">
                            <div class="col-lg-6">
                                <h2 class="mb-0">Yazılar</h2>
                            </div>
                            <div class="col-lg-6">
                                <ul class="nav justify-content-end">
                                    @if ($remainingPosts > 0)
                                        <li class="nav-item">
                                            <span class="text-muted">Kalan Kullanım: {{ $remainingPosts }} / </span>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('app.post.create') }}" class="btn" title="Yeni Sayfa">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                                </svg>
                                                Yazı Ekle
                                            </a>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <div class="alert alert-warning mb-0">
                                                İçerik oluşturma limitinize ulaştınız
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>Blog Yazıları</h2>
                            @if ($remainingPosts > 0)
                                <div class="d-flex align-items-center gap-3">
                                    <span class="text-muted">Kalan hak: {{ $remainingPosts }}</span>
                                    <a href="{{ route('app.post.create') }}" class="btn btn-primary">
                                        Yeni Blog Yazısı
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    Blog yazısı oluşturma limitinize ulaştınız
                                </div>
                            @endif
                        </div>
                    @endif
                    <div class="table-responsive {{ !$hasFeature ? 'blur-table' : '' }}">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="w-90">Yazı Adı</th>
                                    <th class="w-10"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $post)
                                    <tr>
                                        <td>{{ $page->title }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('app.post.edit', $post->id) }}" class="btn btn-sm"
                                                title="Yazı Bilgileri">
                                                Bilgiler
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
