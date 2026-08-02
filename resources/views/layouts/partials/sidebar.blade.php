<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @php $svc = app(\App\Services\SettingsService::class); @endphp
    <a href="{{ route('dashboard') }}" class="brand-link">
        @if($svc->get('company_logo') && \Illuminate\Support\Facades\Storage::disk('public')->exists($svc->get('company_logo')))
            <img src="{{ asset('storage/'.$svc->get('company_logo')) }}" alt="Logo" class="brand-image" style="max-height:35px; max-width:150px; object-fit:contain;">
        @else
            <img src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        @endif
        <span class="brand-text font-weight-light">{{ $svc->get('company_name', config('app.name')) }}</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user-circle fa-2x"></i>
            </div>
            <div class="info">
                <a href="{{ route('profile.edit') }}" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p>
                    </a>
                </li>
                @include('layouts.partials.sidebar-nav')
            </ul>
        </nav>
    </div>
</aside>