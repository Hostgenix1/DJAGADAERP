@php
    /** Shared report page layout. Expects: $title, $headings, $rows, $totals, $meta + slot $filters + optional $extra */
@endphp
@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> {{ $title }}</h3>
                <div class="card-tools">
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'xlsx']) }}" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Excel</a>
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-info btn-sm"><i class="fas fa-file-csv"></i> CSV</a>
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> PDF</a>
                </div>
            </div>
            <div class="card-body">
                @yield('filters')
                @hasSection('extra')
                    @yield('extra')
                @endif

                @if(!empty($meta))
                    <div class="mb-2">
                        @foreach($meta as $k => $v)
                            <span class="badge badge-light border mr-1">{{ $k }}: <strong>{{ $v }}</strong></span>
                        @endforeach
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                @foreach($headings as $h)
                                    <th class="{{ $loop->iteration > 1 ? 'text-right' : '' }}">{{ $h }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td class="{{ $loop->iteration > 1 ? 'text-right' : '' }}">{!! $cell !!}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr><td colspan="{{ count($headings) }}" class="text-center text-muted">No data for this period.</td></tr>
                            @endforelse
                        </tbody>
                        @if($totals)
                            <tfoot class="thead-light">
                                <tr>
                                    @foreach($totals as $cell)
                                        <td class="{{ $loop->iteration > 1 ? 'text-right' : '' }}"><strong>{!! $cell !!}</strong></td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
