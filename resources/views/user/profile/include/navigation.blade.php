<ul class="nav navtab-bg nav-pills flex-column" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="{{ route('app.profile') }}" class="nav-link {{ request()->routeIs('app.profile') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-user-check m-n4">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                <path d="M15 19l2 2l4 -4" />
            </svg>
            <span>Bilgilerim</span>
        </a>
    </li>
    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::twoFactorAuthentication()))
        <li class="nav-item" role="presentation">
            <a href="{{ route('app.profile.twofactor') }}"
                class="nav-link {{ request()->routeIs('app.profile.twofactor') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-auth-2fa m-n4">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 16h-4l3.47 -4.66a2 2 0 1 0 -3.47 -1.54" />
                    <path d="M10 16v-8h4" />
                    <path d="M10 12l3 0" />
                    <path d="M17 16v-6a2 2 0 0 1 4 0v6" />
                    <path d="M17 13l4 0" />
                </svg>
                <span>İki Faktörlü Doğrulama</span>
            </a>
        </li>
    @endif
    <li class="nav-item" role="presentation">
        <a href="{{ route('app.profile.activitylogs') }}"
            class="nav-link {{ request()->routeIs('app.profile.activitylogs') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-activity m-n4">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 12h4l3 8l4 -16l3 8h4" />
            </svg>
            <span>İşlem Kayıtları</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('app.profile.authlogs') }}"
            class="nav-link {{ request()->routeIs('app.profile.authlogs') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-user-scan m-n4">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M10 9a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                <path d="M4 8v-2a2 2 0 0 1 2 -2h2" />
                <path d="M4 16v2a2 2 0 0 0 2 2h2" />
                <path d="M16 4h2a2 2 0 0 1 2 2v2" />
                <path d="M16 20h2a2 2 0 0 0 2 -2v-2" />
                <path d="M8 16a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2" />
            </svg>
            <span>Oturum Kayıtları</span>
        </a>
    </li>
</ul>
