@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- Row 1: Main Financial KPIs --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($stats['total_revenue'], 2) }}</h3>
                <p>Total Revenue</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <a href="{{ route('payments.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($stats['outstanding'], 2) }}</h3>
                <p>Outstanding Invoices</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <a href="{{ route('invoices.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($stats['monthly_sales'], 2) }}</h3>
                <p>Monthly Sales</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="{{ route('invoices.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($stats['total_expenses'], 2) }}</h3>
                <p>Total Expenses</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <a href="{{ route('payments.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

{{-- Row 2: Invoice Status + Financial Overview --}}
<div class="row">
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Overdue</span>
                <span class="info-box-number">{{ $stats['overdue'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Partially Paid</span>
                <span class="info-box-number">{{ $stats['partially_paid'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Fully Paid</span>
                <span class="info-box-number">{{ $stats['fully_paid'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-money-bill"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Monthly Revenue</span>
                <span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($stats['monthly_revenue'], 2) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Row 3: Business Stats --}}
<div class="row">
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Customers</span>
                <span class="info-box-number">{{ $stats['customers'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-secondary"><i class="fas fa-truck"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Suppliers</span>
                <span class="info-box-number">{{ $stats['suppliers'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Products</span>
                <span class="info-box-number">{{ $stats['products'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-file-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pending Quotes</span>
                <span class="info-box-number">{{ $stats['pending_quotes'] }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Row 4: Orders, Shipments & Leads --}}
<div class="row">
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-clipboard-list"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Active Orders</span>
                <span class="info-box-number">{{ $stats['active_orders'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-shipping-fast"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Shipments In Transit</span>
                <span class="info-box-number">{{ $stats['shipments_in_transit'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-handshake"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Active Leads</span>
                <span class="info-box-number">{{ $stats['active_leads'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-secondary"><i class="fas fa-calendar-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Monthly Expenses</span>
                <span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($stats['monthly_expenses'], 2) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Row 5: Currency-wise Breakdown --}}
<div class="row">
    <div class="col-md-6">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-coins mr-1"></i> Revenue by Currency</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Currency</th>
                            <th class="text-right">Received</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($revenueByCurrency as $rbc)
                        <tr>
                            <td><strong>{{ $rbc['code'] }}</strong> <span class="text-muted">({{ $rbc['symbol'] }})</span></td>
                            <td class="text-right">{{ $rbc['symbol'] }}{{ number_format($rbc['total'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted text-center">No payment data yet</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-danger card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exclamation-circle mr-1"></i> Outstanding by Currency</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Currency</th>
                            <th class="text-right">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($outstandingByCurrency as $obc)
                        <tr>
                            <td><strong>{{ $obc['code'] }}</strong> <span class="text-muted">({{ $obc['symbol'] }})</span></td>
                            <td class="text-right">{{ $obc['symbol'] }}{{ number_format($obc['total'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted text-center">No outstanding invoices</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Row 5b: Payables (Supplier Bills) --}}
<div class="row">
    <div class="col-md-6">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-hand-holding-usd mr-1"></i> Payables by Currency</h3>
                <div class="card-tools">
                    <span class="badge badge-danger">{{ $payablesSummary['count'] ?? 0 }} open bills</span>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Currency</th>
                            <th class="text-right">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($payablesByCurrency as $pbc)
                        <tr>
                            <td><strong>{{ $pbc['code'] }}</strong> <span class="text-muted">({{ $pbc['symbol'] }})</span></td>
                            <td class="text-right">{{ $pbc['symbol'] }}{{ number_format($pbc['total'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted text-center">No outstanding supplier bills</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-1"></i> Recent Supplier Bills</h3>
                <div class="card-tools">
                    <a href="{{ route('supplier_bills.index') }}" class="btn btn-xs btn-default">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Supplier</th>
                            <th class="text-right">Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($recentBills as $b)
                        <tr>
                            <td><a href="{{ route('supplier_bills.show', $b['id']) }}">{{ $b['number'] }}</a></td>
                            <td>{{ $b['supplier']['company_name'] ?? '-' }}</td>
                            <td class="text-right">{{ $b['currency']['symbol'] ?? $defaultCurrency?->symbol ?? '$' }}{{ number_format($b['total'], 2) }}</td>
                            <td>
                                @php $badge = ['draft' => 'badge-secondary', 'confirmed' => 'badge-info', 'partial' => 'badge-warning', 'paid' => 'badge-success', 'cancelled' => 'badge-danger'][$b['status']] ?? 'badge-secondary'; @endphp
                                <span class="badge {{ $badge }}">{{ ucfirst($b['status']) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted text-center">No supplier bills yet</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Row 6: Revenue Chart + Cash Flow Chart --}}
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Monthly Revenue (Last 12 Months)</h3>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Cash Flow (Last 12 Months)</h3>
            </div>
            <div class="card-body">
                <canvas id="cashFlowChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Row 6: Lead Pipeline + Top Products --}}
<div class="row">
    <div class="col-md-5">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Lead Pipeline</h3>
            </div>
            <div class="card-body">
                <canvas id="pipelineChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-star mr-1"></i> Top Selling Products</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-right">Qty Sold</th>
                            <th class="text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($topProducts as $tp)
                        <tr>
                            <td>{{ $tp['name'] ?? '-' }}</td>
                            <td class="text-right">{{ number_format($tp['total_qty']) }}</td>
                            <td class="text-right">{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($tp['total_revenue'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted text-center">No sales data yet</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Row 7: Top Customers + Recent Activity --}}
<div class="row">
    <div class="col-md-6">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-trophy mr-1"></i> Top Customers</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th class="text-right">Total Invoiced</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($topCustomers as $c)
                        <tr>
                            <td>
                                <a href="{{ route('customers.show', $c['id']) }}">{{ $c['company_name'] ?? '-' }}</a>
                            </td>
                            <td class="text-right">{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($c['invoices_sum_total'] ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted text-center">No data yet</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clock mr-1"></i> Recent Activity</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                @forelse($activities as $a)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong>{{ $a['causer']['name'] ?? 'System' }}</strong>
                            <small class="text-muted">{{ $a['created_at'] }}</small>
                        </div>
                        <span class="text-muted">{{ $a['description'] ?? '' }}</span>
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

new Chart(document.getElementById('cashFlowChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($cashFlow['labels']) !!},
        datasets: [
            {
                label: 'Revenue',
                data: {!! json_encode($cashFlow['revenue']) !!},
                backgroundColor: 'rgba(40,167,69,0.7)',
                borderColor: '#28a745',
                borderWidth: 1
            },
            {
                label: 'Expenses',
                data: {!! json_encode($cashFlow['expenses']) !!},
                backgroundColor: 'rgba(220,53,69,0.7)',
                borderColor: '#dc3545',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
    }
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
