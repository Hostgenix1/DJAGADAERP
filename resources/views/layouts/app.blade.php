{{--
  Main Application Layout
  Module: Layouts
  Features: AdminLTE dashboard layout, sidebar navigation, top navbar, breadcrumb, flash messages, footer, CSRF token setup, jQuery/Axios initialization
  Version: 1.0.0
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) — {{ app(\App\Services\SettingsService::class)->get('company_name', config('app.name')) }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    @include('layouts.partials.navbar')

    @include('layouts.partials.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            @hasSection('breadcrumb')
                                <li class="breadcrumb-item">@yield('breadcrumb')</li>
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @include('layouts.partials.flash')
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">{{ config('app.version', 'v2.2.0') }}</div>
        <strong>Copyright &copy; {{ date('Y') }} {{ config('app.name') }}.</strong> All rights reserved.
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<script>
    window._csrf = '{{ csrf_token() }}';
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': window._csrf } });
</script>
<script>
/* Global rule: no backdated entries. In data-entry (POST) forms, date /
   datetime / month inputs cannot go below today (server validates too).
   Add data-allow-past="1" to an input to opt out (e.g. employee hire date). */
(function () {
    function pad(n) { return (n < 10 ? '0' : '') + n; }
    const now = new Date();
    const today = now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate());
    const thisMonth = now.getFullYear() + '-' + pad(now.getMonth() + 1);
    function guard(input) {
        if (input.dataset.allowPast === '1' || input.readOnly || input.disabled) return;
        let min = null;
        if (input.type === 'date' || input.type === 'datetime-local') min = today;
        else if (input.type === 'month') min = thisMonth;
        else if (input.type === 'time') return; // time-of-day is not a backdate
        else return;
        if (!input.min || input.min < min) { try { input.min = min; } catch (e) {} }
        input.addEventListener('change', function () {
            if (input.value && input.value < min) {
                input.value = '';
                input.classList.add('is-invalid');
                setTimeout(function () { input.classList.remove('is-invalid'); }, 2500);
                if (window.toastr) { toastr.error('Past dates are not allowed.'); }
            }
        });
    }
    function applyAll() {
        // Data-entry forms only: skip GET search/filter forms (method defaults to GET).
        document.querySelectorAll('form').forEach(function (form) {
            const m = (form.getAttribute('method') || 'GET').toUpperCase();
            if (m === 'GET' && !form.querySelector('input[name="_token"], input[name="_method"]')) return;
            form.querySelectorAll('input[type="date"], input[type="datetime-local"], input[type="month"]').forEach(guard);
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', applyAll);
    else applyAll();
})();
</script>
@stack('scripts')
</body>
</html>