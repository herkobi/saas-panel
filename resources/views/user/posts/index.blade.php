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
                                        <td>{{ $post->title }}</td>
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
