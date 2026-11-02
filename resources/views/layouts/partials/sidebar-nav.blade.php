@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';

    // One colour per business area. Falls back to a palette for new groups.
    $groupColors = [
        'CRM' => '#60a5fa',
        'Products' => '#a78bfa',
        'Quotations' => '#34d399',
        'Invoicing' => '#2dd4bf',
        'Orders' => '#fb923c',
        'Shipments' => '#38bdf8',
        'Inventory' => '#f59e0b',
        'Purchasing' => '#f472b6',
        'Payments' => '#4ade80',
        'Expenses' => '#f87171',
        'Pricing' => '#c084fc',
        'HR & Payroll' => '#e879f9',
        'Documents' => '#94a3b8',
        'Reports' => '#facc15',
        'Settings' => '#a3e635',
        'User Management' => '#818cf8',
    ];
    $fallbackColors = ['#60a5fa', '#a78bfa', '#34d399', '#fbbf24', '#f472b6', '#22d3ee', '#fb923c', '#4ade80'];
@endphp

@foreach (config('menu', []) as $group)
    @php
        $visibleItems = collect($group['items'] ?? [])
            ->filter(fn ($item) => auth()->user()->can($item['permission'] ?? 'view-none'))
            ->values()
            ->all();
        $isActive = collect($visibleItems)->contains(fn ($item) => str_starts_with($currentRoute ?? '', Str::beforeLast($item['route'], '.')));
        $iconColor = $groupColors[$group['label']] ?? $fallbackColors[$loop->index % count($fallbackColors)];
    @endphp
    @if (count($visibleItems) > 0)
        <li class="nav-item {{ $isActive ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $isActive ? 'active' : '' }}">
                <i class="nav-icon fas {{ $group['icon'] ?? 'fa-folder-open' }}" style="color: {{ $iconColor }};"></i>
                <p>{{ $group['label'] }}<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
                @foreach ($visibleItems as $item)
                    <li class="nav-item">
                        <a href="{{ route($item['route']) }}"
                           class="nav-link {{ $currentRoute === $item['route'] ? 'active' : '' }}">
                            <i class="nav-icon fas {{ $item['icon'] ?? 'fa-circle' }}" style="color: {{ $iconColor }};"></i>
                            <p>{{ $item['label'] }}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endif
@endforeach
