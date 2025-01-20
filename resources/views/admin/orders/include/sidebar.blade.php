<ul class="list-group list-group-flush mb-3">
    <li class="list-group-item {{ request()->routeIs('panel.orders') ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.orders') }}" title="Son Ödemeler">
            Son Ödemeler
        </a>
    </li>
    <li class="list-group-item {{ request()->routeIs('panel.order.invoiced') ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.order.invoiced') }}" title="Faturalandırılmış Ödemeler">
            Faturalandırılmış Ödemeler
        </a>
    </li>
    <li class="list-group-item {{ request()->routeIs('panel.order.pending') ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.order.pending') }}" title="Onay Bekleyen Ödemeler">
            Onay Bekleyen Ödemeler
        </a>
    </li>
    <li class="list-group-item {{ request()->routeIs('panel.order.rejected') ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.order.rejected') }}" title="İptal Edilmiş Ödemeler">
            İptal Edilmiş Ödemeler
        </a>
    </li>
</ul>
