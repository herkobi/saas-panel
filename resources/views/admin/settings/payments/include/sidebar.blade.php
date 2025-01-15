<ul class="list-group list-group-flush">
    <li
        class="list-group-item {{ request()->routeIs(['panel.settings.payments.bacs', 'panel.settings.payments.bac.*']) ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.settings.payments.bacs') }}" title="Genel Ayarlar">Banka Hesap Bilgileri</a>
    </li>
    <li class="list-group-item {{ request()->routeIs('panel.settings.payments.paytr') ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.settings.payments.paytr') }}" title="Sistem Ayarları">PayTR Bilgileri</a>
    </li>
</ul>
