@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="row">
    @php
        $cards = [
            ['title' => 'Sales Report', 'desc' => 'Invoices, payments and outstanding by period and customer.', 'route' => 'reports.sales', 'icon' => 'fa-file-invoice-dollar', 'color' => 'bg-primary'],
            ['title' => 'Financial Report', 'desc' => 'Profit & Loss by month: revenue, costs, expenses, salaries, profit.', 'route' => 'reports.financial', 'icon' => 'fa-chart-line', 'color' => 'bg-success'],
            ['title' => 'Customer Report', 'desc' => 'Invoiced, paid and outstanding balances per customer.', 'route' => 'reports.customers', 'icon' => 'fa-users', 'color' => 'bg-info'],
            ['title' => 'Supplier Report', 'desc' => 'Purchase orders, supplier bills and balances owed.', 'route' => 'reports.suppliers', 'icon' => 'fa-truck', 'color' => 'bg-warning'],
            ['title' => 'Inventory Report', 'desc' => 'Stock on hand, reorder levels, stock valuation and alerts.', 'route' => 'reports.inventory', 'icon' => 'fa-boxes', 'color' => 'bg-danger'],
            ['title' => 'Payroll Report', 'desc' => 'Salaries, allowances, deductions and net pay per employee.', 'route' => 'reports.employees', 'icon' => 'fa-money-check-alt', 'color' => 'bg-dark'],
        ];
    @endphp
    @foreach($cards as $card)
        <div class="col-lg-4 col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="btn {{ $card['color'] }} btn-circle mr-2"><i class="fas {{ $card['icon'] }}"></i></span>
                        <h5 class="m-0">{{ $card['title'] }}</h5>
                    </div>
                    <p class="text-muted mb-2">{{ $card['desc'] }}</p>
                    <a href="{{ route($card['route']) }}" class="btn btn-sm btn-outline-primary">Open Report <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-12">
        <div class="alert alert-info mb-0">
            <i class="fas fa-download mr-1"></i> Every report can be exported to <strong>Excel</strong>, <strong>CSV</strong> or <strong>PDF</strong> using the export buttons inside each report. Multi-currency amounts are converted to your base currency.
        </div>
    </div>
</div>
@endsection
