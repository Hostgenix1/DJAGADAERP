@extends('layouts.app')
@section('title', 'Dashboard')

{{--
  Dashboard - Main Overview
  Module: Dashboard
  Features: KPI cards (revenue, customers, outstanding, overdue), mini stat cards (products, leads, quotes, expenses), revenue line chart, lead pipeline doughnut chart, top customers table, recent activity feed
  Version: 2.0.0
--}}

@section('content')
<div class="dashboard">

    {{-- TOP KPI CARDS --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:58px;height:58px;background:linear-gradient(135deg,#667eea,#764ba2);">
                        <i class="fas fa-dollar-sign text-white" style="font-size:1.3rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">Total Revenue</p>
                        <h4 class="font-weight-bold text-dark mb-0">${{ number_format($stats['total_revenue'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:58px;height:58px;background:linear-gradient(135deg,#11998e,#38ef7d);">
                        <i class="fas fa-users text-white" style="font-size:1.3rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">Customers</p>
                        <h4 class="font-weight-bold text-dark mb-0">{{ $stats['customers'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:58px;height:58px;background:linear-gradient(135deg,#f093fb,#f5576c);">
                        <i class="fas fa-receipt text-white" style="font-size:1.3rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">Outstanding</p>
                        <h4 class="font-weight-bold text-dark mb-0">${{ number_format($stats['outstanding'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:58px;height:58px;background:linear-gradient(135deg,#fa709a,#fee140);">
                        <i class="fas fa-exclamation-triangle text-white" style="font-size:1.3rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px;">Overdue</p>
                        <h4 class="font-weight-bold text-dark mb-0">{{ $stats['overdue'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MINI STAT CARDS --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:12px;">
                <div class="card-body py-3 px-4 d-flex align-items-center">
                    <div class="mr-3 rounded d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(0,123,255,.1);">
                        <i class="fas fa-box text-primary"></i>
                    </div>
                    <div>
                        <div style="font-size:0.78rem; color:#6c757d; text-transform:uppercase; letter-spacing:0.3px;">Products</div>
                        <div class="font-weight-bold" style="font-size:1.25rem;">{{ $stats['products'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:12px;">
                <div class="card-body py-3 px-4 d-flex align-items-center">
                    <div class="mr-3 rounded d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(23,162,184,.1);">
                        <i class="fas fa-handshake text-info"></i>
                    </div>
                    <div>
                        <div style="font-size:0.78rem; color:#6c757d; text-transform:uppercase; letter-spacing:0.3px;">Active Leads</div>
                        <div class="font-weight-bold" style="font-size:1.25rem;">{{ $stats['active_leads'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:12px;">
                <div class="card-body py-3 px-4 d-flex align-items-center">
                    <div class="mr-3 rounded d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(255,193,7,.1);">
                        <i class="fas fa-file-alt text-warning"></i>
                    </div>
                    <div>
                        <div style="font-size:0.78rem; color:#6c757d; text-transform:uppercase; letter-spacing:0.3px;">Pending Quotes</div>
                        <div class="font-weight-bold" style="font-size:1.25rem;">{{ $stats['pending_quotes'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:12px;">
                <div class="card-body py-3 px-4 d-flex align-items-center">
                    <div class="mr-3 rounded d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(40,167,69,.1);">
                        <i class="fas fa-money-bill text-success"></i>
                    </div>
                    <div>
                        <div style="font-size:0.78rem; color:#6c757d; text-transform:uppercase; letter-spacing:0.3px;">Expenses</div>
                        <div class="font-weight-bold" style="font-size:1.25rem;">${{ number_format($stats['total_expenses'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">
                <div class="card-header border-0 pt-4 pb-2" style="background:transparent;">
                    <h5 class="font-weight-bold text-dark mb-0" style="font-size:1rem;">
                        <i class="fas fa-chart-line mr-2 text-primary"></i>Revenue (Last 12 Months)
                    </h5>
                </div>
                <div class="card-body pt-2">
                    <canvas id="revenueChart" height="280"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">
                <div class="card-header border-0 pt-4 pb-2" style="background:transparent;">
                    <h5 class="font-weight-bold text-dark mb-0" style="font-size:1rem;">
                        <i class="fas fa-chart-pie mr-2 text-info"></i>Lead Pipeline
                    </h5>
                </div>
                <div class="card-body pt-2 d-flex align-items-center justify-content-center">
                    <canvas id="pipelineChart" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLES --}}
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">
                <div class="card-header border-0 pt-4 pb-2" style="background:transparent;">
                    <h5 class="font-weight-bold text-dark mb-0" style="font-size:1rem;">
                        <i class="fas fa-trophy mr-2 text-warning"></i>Top Customers
                    </h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background:#f8f9fa; border-top: 1px solid #e9ecef; border-bottom: 2px solid #dee2e6;">
                                <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px; padding:12px 16px;">Customer</th>
                                <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px; padding:12px 16px; text-align:right;">Total Invoiced</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($topCustomers as $i => $c)
                        <tr>
                            <td style="padding:12px 16px;">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-2 text-white font-weight-bold" style="width:34px;height:34px;background:linear-gradient(135deg,#667eea,#764ba2);font-size:0.8rem;">
                                        {{ strtoupper(substr($c['company_name'] ?? 'N', 0, 1)) }}
                                    </div>
                                    <a href="{{ route('customers.show', $c['id']) }}" class="text-dark font-weight-500 text-decoration-none">{{ $c['company_name'] ?? '-' }}</a>
                                </div>
                            </td>
                            <td class="text-right font-weight-bold" style="padding:12px 16px;">${{ number_format($c['invoices_sum_total'] ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-muted text-center py-4">No data yet</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">
                <div class="card-header border-0 pt-4 pb-2" style="background:transparent;">
                    <h5 class="font-weight-bold text-dark mb-0" style="font-size:1rem;">
                        <i class="fas fa-clock mr-2 text-success"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                    @forelse($activities as $a)
                        <li class="list-group-item d-flex align-items-start py-3" style="border-left:none; border-right:none;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width:36px;height:36px;background:rgba(40,167,69,.1);font-size:0.7rem;">
                                <i class="fas fa-circle text-success" style="font-size:0.4rem;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark" style="font-size:0.9rem;">{{ $a['causer']['name'] ?? 'System' }}</strong>
                                    <small class="text-muted" style="font-size:0.75rem;">{{ $a['created_at'] }}</small>
                                </div>
                                <p class="text-muted mb-0" style="font-size:0.85rem;">{{ $a['description'] ?? '' }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center py-4">No activity yet</li>
                    @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .dashboard .card { transition: transform .2s, box-shadow .2s; }
    .dashboard .card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,.08) !important; }
    .dashboard .list-group-item { border-left: none; border-right: none; }
    .dashboard .list-group-item + .list-group-item { border-top: 1px solid #f0f0f0; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
const gradient = document.getElementById('revenueChart').getContext('2d');
const g = gradient.createLinearGradient(0, 0, 0, 280);
g.addColorStop(0, 'rgba(102,126,234,0.25)');
g.addColorStop(1, 'rgba(102,126,234,0.02)');

new Chart(gradient, {
    type: 'line',
    data: {
        labels: {!! json_encode($revenue['labels']) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode($revenue['data']) !!},
            borderColor: '#667eea',
            backgroundColor: g,
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#667eea',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#6c757d' } },
            y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 }, color: '#6c757d', callback: v => '$' + v.toLocaleString() }, beginAtZero: true }
        }
    }
});

new Chart(document.getElementById('pipelineChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($pipeline['labels']) !!},
        datasets: [{
            data: {!! json_encode($pipeline['data']) !!},
            backgroundColor: ['#6c757d','#667eea','#38ef7d','#f5576c','#fee140'],
            borderWidth: 0,
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } } }
        }
    }
});
</script>
@endpush
