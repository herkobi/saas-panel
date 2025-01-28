<div class="sidebar">
    <div class="logo px-3 py-2">
        <img src="{{ Setting::getFullPath('logo') }}" alt="{{ Setting::get('title') }}" class="img-fluid w-75">
    </div>
    <ul class="menu">
        <li {{ request()->routeIs('app.home') ? 'class=active' : '' }}>
            <a href="{{ route('app.home') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-house" viewBox="0 0 20 20">
                    <path
                        d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
                </svg>
                Başlangıç
            </a>
        </li>
        <li class="title">İçerikler</li>
        <li {{ request()->routeIs(['app.posts', 'app.post.edit']) ? 'class=active' : '' }}>
            <a href="{{ route('app.posts') }}" title="İçerikler">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-check2-square" viewBox="0 0 20 20">
                    <path
                        d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z" />
                    <path
                        d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0" />
                </svg>
                İçerikler
            </a>
        </li>
        <li {{ request()->routeIs(['app.post.create']) ? 'class=active' : '' }}>
            <a href="{{ route('app.post.create') }}" title="Yeni İçerik Ekle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-plus-circle" viewBox="0 0 20 20">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path
                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg>
                Yeni İçerik Ekle
            </a>
        </li>
    </ul>
</div>
