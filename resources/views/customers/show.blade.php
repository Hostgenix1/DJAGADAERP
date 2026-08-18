@extends('layouts.app')

@section('title', $customer->company_name)

{{--
  Customer Detail - CRM Module
  Module: CRM
  Features: Customer profile card, contact info, quick actions (WhatsApp/Email/Call), contacts list, activity timeline, communication log modal, document upload, status badge
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">{{ $customer->company_name }}</h3>
                <div class="card-tools">
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><th>Contact</th><td>{{ $customer->contact_person }}</td></tr>
                    <tr><th>Email</th><td><a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a></td></tr>
                    <tr><th>Phone</th><td>{{ $customer->phone }}</td></tr>
                    <tr><th>City</th><td>{{ $customer->city }}</td></tr>
                    <tr><th>Country</th><td>{{ $customer->country }}</td></tr>
                    <tr><th>Currency</th><td>{{ $customer->currency?->code }}</td></tr>
                    <tr><th>Status</th><td>{!! $customer->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>' !!}</td></tr>
                    <tr><th>Address</th><td>{{ $customer->address }}</td></tr>
                </table>
            </div>
        </div>

        @if($customer->phone)
        <div class="card card-outline card-success">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-bolt mr-1"></i> Quick Actions</h3></div>
            <div class="card-body p-2">
                <a href="https://wa.me/{{ ltrim(preg_replace('/[^0-9]/', '', $customer->phone), '0') }}" target="_blank" class="btn btn-success btn-sm btn-block"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                <a href="mailto:{{ $customer->email }}" class="btn btn-info btn-sm btn-block"><i class="fas fa-envelope"></i> Email</a>
                <a href="tel:{{ $customer->phone }}" class="btn btn-warning btn-sm btn-block"><i class="fas fa-phone"></i> Call</a>
            </div>
        </div>
        @endif

        <div class="card card-outline card-info">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-address-book mr-1"></i> Contacts</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    @forelse($customer->contacts as $contact)
                        <tr>
                            <td>
                                <strong>{{ $contact->full_name }}</strong><br>
                                <small class="text-muted">{{ $contact->position }}</small><br>
                                <small>{{ $contact->email }} {{ $contact->phone ? '| '.$contact->phone : '' }}</small>
                                @if($contact->is_primary) <span class="badge badge-primary">Primary</span> @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-muted">No contacts</td></tr>
                    @endforelse
                </table>
            </div>
        </div>

        @include('documents.partials._upload', ['morphType' => 'customer', 'morphClass' => 'App\\Models\\Customer', 'entity' => $customer, 'documents' => $customer->documents])
    </div>

    <div class="col-lg-8">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-stream mr-1"></i> Timeline</h3>
                <div class="card-tools">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addCommunication">
                        <i class="fas fa-plus"></i> Log Activity
                    </button>
                </div>
            </div>
            <div class="card-body">
                @forelse($timeline as $event)
                    <div class="timeline-item">
                        <div class="time-label"><span class="badge badge-{{ $event['color'] }}"><i class="fas {{ $event['icon'] }} mr-1"></i> {{ ucfirst($event['type']) }}</span></div>
                        <div>
                            <h4 class="timeline-header">{{ $event['title'] }}</h4>
                            @if($event['body'])<div class="timeline-body text-muted">{{ $event['body'] }}</div>@endif
                            <small class="text-muted">{{ \Carbon\Carbon::parse($event['date'])->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">No activity recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Financial Summary --}}
<div class="row mt-3">
    <div class="col-lg-4 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-file-invoice-dollar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Invoiced</span>
                <span class="info-box-number">{{ number_format($totalInvoiced, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-hand-holding-usd"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Paid</span>
                <span class="info-box-number">{{ number_format($totalPaid, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="info-box">
            <span class="info-box-icon {{ $outstanding > 0 ? 'bg-danger' : 'bg-secondary' }}"><i class="fas fa-hourglass-half"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Outstanding Balance</span>
                <span class="info-box-number">{{ number_format($outstanding, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-7">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-invoice mr-1"></i> Invoices</h3>
                <div class="card-tools">
                    <a href="{{ route('invoices.create', ['customer_id' => $customer->id]) }}" class="btn btn-xs btn-primary"><i class="fas fa-plus"></i> New Invoice</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Invoice</th><th>Date</th><th>Status</th><th class="text-right">Total</th><th class="text-right">Paid</th><th class="text-right">Balance</th></tr></thead>
                    <tbody>
                    @forelse($invoices as $inv)
                        <tr>
                            <td><a href="{{ route('invoices.show', $inv) }}">{{ $inv->number }}</a></td>
                            <td>{{ $inv->invoice_date?->format('d M Y') }}</td>
                            <td>{!! $inv->status_badge !!}</td>
                            <td class="text-right">{{ $inv->currency?->code ?? '' }} {{ number_format($inv->total, 2) }}</td>
                            <td class="text-right">{{ $inv->currency?->code ?? '' }} {{ number_format($inv->paid_amount, 2) }}</td>
                            <td class="text-right">{{ $inv->currency?->code ?? '' }} {{ number_format($inv->balance, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No invoices yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-money-check-alt mr-1"></i> Payments Received</h3>
                <div class="card-tools">
                    <a href="{{ route('payments.create', ['type' => 'customer', 'customer_id' => $customer->id]) }}" class="btn btn-xs btn-success"><i class="fas fa-plus"></i> Record Payment</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Payment</th><th>Date</th><th>Method</th><th class="text-right">Amount</th><th>Reference</th></tr></thead>
                    <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td><a href="{{ route('payments.show', $payment) }}">{{ $payment->number }}</a></td>
                            <td>{{ $payment->paid_on?->format('d M Y') }}</td>
                            <td>{{ ucfirst($payment->method) }}</td>
                            <td class="text-right">{{ $payment->currency?->code ?? '' }} {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->reference ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No payments recorded.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCommunication" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('communications.store') }}" method="POST">
                @csrf
                <input type="hidden" name="communicable_type" value="App\Models\Customer">
                <input type="hidden" name="communicable_id" value="{{ $customer->id }}">
                <div class="modal-header"><h5 class="modal-title">Log Activity</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Type</label>
                            <select name="type" class="form-control" required>
                                <option value="call">Call</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="email">Email</option>
                                <option value="meeting">Meeting</option>
                                <option value="note">Note</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Direction</label>
                            <select name="direction" class="form-control">
                                <option value="outbound">Outbound</option>
                                <option value="inbound">Inbound</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Details</label>
                        <textarea name="body" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Date/Time</label>
                        <input type="datetime-local" name="occurred_at" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
