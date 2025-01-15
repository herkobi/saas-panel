<ul class="list-group list-group-flush mb-3">
    <li
        class="list-group-item {{ request()->routeIs(['panel.tools.config.countries', 'panel.tools.config.country.*']) ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.tools.config.countries') }}" title="Ülkeler">Ülkeler</a>
    </li>
    <li
        class="list-group-item {{ request()->routeIs(['panel.tools.config.languages', 'panel.tools.config.language.*']) ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.tools.config.languages') }}" title="Diller">Diller</a>
    </li>
    <li
        class="list-group-item {{ request()->routeIs(['panel.tools.config.currencies', 'panel.tools.config.currency.*']) ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.tools.config.currencies') }}" title="Para Birimleri">Para Birimleri</a>
    </li>
</ul>

<ul class="list-group list-group-flush mb-3">
    <li
        class="list-group-item {{ request()->routeIs(['panel.tools.config.taxes', 'panel.tools.config.tax.*']) ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.tools.config.taxes') }}" title="Vergi Oranları">Vergi Oranları</a>
    </li>
    <li
        class="list-group-item {{ request()->routeIs(['panel.tools.config.orderstatuses', 'panel.tools.config.orderstatus.*']) ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.tools.config.orderstatuses') }}" title="Ödeme Durumları">Ödeme Durumları</a>
    </li>
</ul>
