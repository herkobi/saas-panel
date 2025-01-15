<ul class="list-group list-group-flush">
    <li
        class="list-group-item {{ request()->routeIs(['panel.settings.agreements.user', 'panel.settings.agreement.user.*']) ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.settings.agreements.user') }}" title="Kullanıcı Sözleşmeleri">Kullanıcı Sözleşmeleri</a>
    </li>
    <li
        class="list-group-item {{ request()->routeIs(['panel.settings.agreements.admin', 'panel.settings.agreement.admin.*']) ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.settings.agreements.admin') }}" title="Yönetici Sözleşmeleri">Yönetici Sözleşmeleri</a>
    </li>
    <li class="list-group-item {{ request()->routeIs('panel.settings.agreement.signatures') ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.settings.agreement.signatures') }}" title="İmzalar">İmzalar</a>
    </li>
</ul>
