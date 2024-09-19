<ul class="nav navtab-bg nav-pills flex-column" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="{{ route('app.account.plans') }}"
            class="nav-link {{ request()->routeIs('app.account.plans') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus m-n4">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                <path d="M16 19h6" />
                <path d="M19 16v6" />
                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
            </svg>
            <span>Paketler</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('app.account.payments') }}"
            class="nav-link {{ request()->routeIs('app.account.payments') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-credit-card-pay m-n4">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 19h-6a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" />
                <path d="M3 10h18" />
                <path d="M16 19h6" />
                <path d="M19 16l3 3l-3 3" />
                <path d="M7.005 15h.005" />
                <path d="M11 15h2" />
            </svg>
            <span>Ödemeler</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('app.account.invoices') }}"
            class="nav-link {{ request()->routeIs('app.account.invoices') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-invoice m-n4">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path
                    d="M19 12v7a1.78 1.78 0 0 1 -3.1 1.4a1.65 1.65 0 0 0 -2.6 0a1.65 1.65 0 0 1 -2.6 0a1.65 1.65 0 0 0 -2.6 0a1.78 1.78 0 0 1 -3.1 -1.4v-14a2 2 0 0 1 2 -2h7l5 5v4.25" />
            </svg>
            <span>Faturalar</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('app.account.invoicedetail') }}"
            class="nav-link {{ request()->routeIs(['app.account.invoicedetail', 'app.account.invoicedetail.*']) ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-address-book m-n4">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M20 6v12a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2z" />
                <path d="M10 16h6" />
                <path d="M13 11m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M4 8h3" />
                <path d="M4 12h3" />
                <path d="M4 16h3" />
            </svg>
            <span>Fatura Bilgileri</span>
        </a>
    </li>
</ul>
