@extends('layouts.app')
@section('title', 'Dashboard')

{{--
  Dashboard - Main Overview
  Module: Dashboard
  Features: KPI cards (revenue, customers, outstanding, overdue), info boxes (products, leads, quotes, expenses), revenue line chart, lead pipeline doughnut chart, top customers table, recent activity feed
  Version: 1.0.0
--}}

@section('content')

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ number_format($stats['total_revenue'], 2) }}</h3><p>Total Revenue</p></div>
            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $stats['customers'] }}</h3><p>Customers</p></div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3>{{ number_format($stats['outstanding'], 2) }}</h3><p>Outstanding</p></div>
            <div class="icon"><i class="fas fa-receipt"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-danger">
            <div class="inner"><h3>{{ $stats['overdue'] }}</h3><p>Overdue Invoices</p></div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-sm-6">
        <div class="info-box"><span class="info-box-icon bg-info"><i class="fas fa-box"></i></span><div class="info-box-content"><span class="info-box-text">Products</span><span class="info-box-number">{{ $stats['products'] }}</span></div></div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box"><span class="info-box-icon bg-primary"><i class="fas fa-handshake"></i></span><div class="info-box-content"><span class="info-box-text">Active Leads</span><span class="info-box-number">{{ $stats['active_leads'] }}</span></div></div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box"><span class="info-box-icon bg-warning"><i class="fas fa-file-invoice"></i></span><div class="info-box-content"><span class="info-box-text">Pending Quotes</span><span class="info-box-number">{{ $stats['pending_quotes'] }}</span></div></div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box"><span class="info-box-icon bg-success"><i class="fas fa-money-bill"></i></span><div class="info-box-content"><span class="info-box-text">Expenses</span><span class="info-box-number">{{ number_format($stats['total_expenses'], 2) }}</span></div></div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Revenue (Last 12 Months)</h3></div>
            <div class="card-body"><canvas id="revenueChart" height="300"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Lead Pipeline</h3></div>
            <div class="card-body"><canvas id="pipelineChart" height="300"></canvas></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card card-secondary card-outline">
            <div class="card-header"><h3 class="card-title">Top Customers</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead><tr><th>Customer</th><th class="text-right">Total Invoiced</th></tr></thead>
                    <tbody>
                    @forelse($topCustomers as $c)
                    <tr>
                        <td><a href="{{ route('customers.show', $c['id']) }}">{{ $c['company_name'] ?? '-' }}</a></td>
                        <td class="text-right">{{ number_format($c['invoices_sum_total'] ?? 0, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-muted text-center">No data yet</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-secondary card-outline">
            <div class="card-header"><h3 class="card-title">Recent Activity</h3></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                @forelse($activities as $a)
                    <li class="list-group-item">
                        <small class="text-muted">{{ $a['created_at'] }}</small><br>
                        <strong>{{ $a['causer']['name'] ?? 'System' }}</strong> {{ $a['description'] ?? '' }}
                    </li>
                @empty
                    <li class="list-group-item text-muted text-center">No activity yet</li>
                @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($revenue['labels']) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode($revenue['data']) !!},
            borderColor: '#007bff',
            backgroundColor: 'rgba(0,123,255,0.1)',
            fill: true,
            tension: 0.3
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('pipelineChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($pipeline['labels']) !!},
        datasets: [{
            data: {!! json_encode($pipeline['data']) !!},
            backgroundColor: ['#6c757d','#17a2b8','#ffc107','#28a745','#dc3545']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush
