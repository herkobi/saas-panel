<ul class="nav navtab-bg nav-pills flex-column" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="{{ route('panel.plans') }}"
            class="nav-link {{ request()->routeIs('panel.plans') ? 'active' : '' }}">
            <span>Planlar</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('panel.plan.create') }}"
            class="nav-link {{ request()->routeIs('panel.plan.create') ? 'active' : '' }}">
            <span>Plan Ekle</span>
        </a>
    </li>
</ul>
