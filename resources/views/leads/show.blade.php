@extends('layouts.app')
@section('title', 'Lead: '.$lead->company_name)
@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-funnel-dollar mr-1"></i> {{ $lead->company_name }}</h3>
                <div class="card-tools">
                    @php
                        $statusBadges = ['new'=>'badge-secondary','contacted'=>'badge-info','qualified'=>'badge-primary','proposal'=>'badge-warning','won'=>'badge-success','lost'=>'badge-danger'];
                    @endphp
                    <span class="badge {{ $statusBadges[$lead->status] ?? 'badge-secondary' }}">{{ ucfirst($lead->status) }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Contact Name:</strong> {{ $lead->contact_name ?: '-' }}<br>
                        <strong>Email:</strong> {{ $lead->email ?: '-' }}<br>
                        <strong>Phone:</strong> {{ $lead->phone ?: '-' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Source:</strong> {{ $lead->source ? ucfirst(str_replace('_', ' ', $lead->source)) : '-' }}<br>
                        <strong>Expected Amount:</strong> {{ $lead->currency ? $lead->currency->code.' ' : '' }}{{ $lead->expected_amount ? number_format($lead->expected_amount, 2) : '-' }}<br>
                        <strong>Expected Close:</strong> {{ $lead->expected_date?->format('d M Y') ?: '-' }}
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6"><strong>Owner:</strong> {{ $lead->owner?->name ?: '-' }}</div>
                    <div class="col-md-6"><strong>Converted Customer:</strong> {{ $lead->customer?->company_name ?: '-' }}</div>
                </div>
                @if($lead->note)
                    <div class="mt-3"><strong>Notes:</strong><br>{!! nl2br(e($lead->note)) !!}</div>
                @endif
                <div class="text-muted mt-3" style="font-size:0.85em;">
                    Created: {{ $lead->created_at?->format('d M Y H:i') }} | Updated: {{ $lead->updated_at?->format('d M Y H:i') }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Actions</h3></div>
            <div class="card-body">
                @can('update-leads')
                    <a href="{{ route('leads.edit', $lead) }}" class="btn btn-info btn-block"><i class="fas fa-edit"></i> Edit</a>
                @endcan
                <a href="{{ route('leads.index') }}" class="btn btn-default btn-block"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
