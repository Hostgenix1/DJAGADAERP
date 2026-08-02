@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

@foreach (config('menu', []) as $group)
    @php
        $visibleItems = collect($group['items'] ?? [])
            ->filter(fn ($item) => auth()->user()->can($item['permission'] ?? 'view-none'))
            ->values()
            ->all();
        $isActive = collect($visibleItems)->contains(fn ($item) => str_starts_with($currentRoute ?? '', Str::beforeLast($item['route'], '.')));
    @endphp
    @if (count($visibleItems) > 0)
        <li class="nav-item {{ $isActive ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $isActive ? 'active' : '' }}">
                <i class="nav-icon fas {{ $group['icon'] ?? 'fa-folder-open' }}"></i>
                <p>{{ $group['label'] }}<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
                @foreach ($visibleItems as $item)
                    <li class="nav-item">
                        <a href="{{ route($item['route']) }}"
                           class="nav-link {{ $currentRoute === $item['route'] ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ $item['label'] }}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endif
@endforeach