<p>Hello {{ $entry->employee?->name }},</p>

<p>Your salary for <strong>{{ $entry->period }}</strong> has been paid.</p>

<p>Net pay: <strong>{{ $entry->currency?->code ?? '' }} {{ number_format((float) $entry->net_salary, 2) }}</strong>
@if($entry->paid_on)(paid on {{ $entry->paid_on->format('d M Y') }})@endif</p>

<p>Your detailed payslip is attached as a PDF.</p>

<p>For any queries, please contact the HR department.</p>
