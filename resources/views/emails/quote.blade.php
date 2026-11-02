<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quotation {{ $quote->number }}</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family:Arial,Helvetica,sans-serif; color:#1f2937;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6; padding:24px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:6px; overflow:hidden;">
                <tr>
                    <td style="background:#1e3a5f; color:#ffffff; padding:20px 24px;">
                        <div style="font-size:18px; font-weight:bold;">{{ app(\App\Services\SettingsService::class)->get('company_name', config('app.name')) }}</div>
                        <div style="font-size:13px; opacity:.85; margin-top:4px;">Quotation {{ $quote->number }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:24px;">
                        <p style="margin:0 0 12px;">Dear {{ $quote->customer?->company_name ?? 'Sir/Madam' }},</p>
                        <p style="margin:0 0 16px; line-height:1.5;">
                            {{ $note ?: 'Please find our quotation attached. We look forward to your confirmation.' }}
                        </p>

                        <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb; border-radius:4px; margin:16px 0;">
                            <tr>
                                <td style="padding:10px 14px; background:#f9fafb; font-size:13px; color:#6b7280;">Quote date</td>
                                <td style="padding:10px 14px; font-size:13px; text-align:right;">{{ $quote->date?->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 14px; background:#f9fafb; font-size:13px; color:#6b7280;">Valid until</td>
                                <td style="padding:10px 14px; font-size:13px; text-align:right;">{{ $quote->valid_until?->format('d M Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 14px; background:#f9fafb; font-size:13px; color:#6b7280;">Total</td>
                                <td style="padding:10px 14px; font-size:15px; font-weight:bold; text-align:right;">{{ $quote->currency?->code }} {{ number_format((float) $quote->total, 2) }}</td>
                            </tr>
                        </table>

                        <p style="margin:0 0 4px; font-size:12px; color:#6b7280;">The quotation PDF is attached to this email.</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 24px; background:#f9fafb; font-size:12px; color:#6b7280;">
                        {{ app(\App\Services\SettingsService::class)->get('company_name', config('app.name')) }}
                        @if(app(\App\Services\SettingsService::class)->get('company_email'))
                            · {{ app(\App\Services\SettingsService::class)->get('company_email') }}
                        @endif
                        @if(app(\App\Services\SettingsService::class)->get('company_phone'))
                            · {{ app(\App\Services\SettingsService::class)->get('company_phone') }}
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
