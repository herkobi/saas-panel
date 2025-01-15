<ul class="list-group list-group-flush">
    <li class="list-group-item {{ request()->routeIs('app.account') ? 'fw-medium' : '' }}">
        <a href="{{ route('app.account') }}" title="Hesap Bilgileri">
            Hesap Bilgileri
        </a>
    </li>
    <li class="list-group-item {{ request()->routeIs('app.account.plans') ? 'fw-medium' : '' }}">
        <a href="{{ route('app.account.plans') }}" title="Planlar">
            Planlar
        </a>
    </li>
    <li class="list-group-item {{ request()->routeIs('app.account.payments') ? 'fw-medium' : '' }}">
        <a href="{{ route('app.account.payments') }}" title="Ödemeler">
            Ödemeler
        </a>
    </li>
</ul>
