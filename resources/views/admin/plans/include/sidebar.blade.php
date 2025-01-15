<ul class="list-group list-group-flush">
    <li class="list-group-item {{ request()->routeIs('panel.plans') ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.plans') }}" title="Planlar">Planlar</a>
    </li>
    <li class="list-group-item {{ request()->routeIs('panel.features') ? 'fw-medium' : '' }}">
        <a href="{{ route('panel.features') }}" title="Özellikler">Özellikler</a>
    </li>
</ul>
