@extends('layouts.app')

@section('title', 'Company Settings')

@section('content')

@php $svc = app(\App\Services\SettingsService::class); @endphp

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle mr-1"></i> Please fix the errors below.
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<form method="POST" action="{{ route('admin.settings.company.update') }}" id="company-form" enctype="multipart/form-data">
    @csrf

    <!-- Company Profile Card -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($svc->get('company_logo'))
                            <img src="{{ asset('storage/'.$svc->get('company_logo')) }}" id="logo-preview"
                                 class="img-circle elevation-2" style="width:120px;height:120px;object-fit:contain;background:#f4f6f9;padding:8px;border-radius:12px;">
                        @else
                            <div id="logo-placeholder" class="mx-auto d-flex align-items-center justify-content-center bg-light"
                                 style="width:120px;height:120px;border-radius:12px;border:2px dashed #dee2e6;">
                                <i class="fas fa-building fa-3x text-muted"></i>
                            </div>
                            <img src="" id="logo-preview" class="img-circle elevation-2 d-none"
                                 style="width:120px;height:120px;object-fit:contain;background:#f4f6f9;padding:8px;border-radius:12px;">
                        @endif
                    </div>

                    <h5 class="font-weight-bold" id="name-display">{{ $svc->get('company_name') ?: 'Your Company' }}</h5>
                    <p class="text-muted mb-1" id="email-display">{{ $svc->get('company_email') ?: 'No email set' }}</p>
                    <p class="text-muted" id="city-display">
                        @if($svc->get('company_city') || $svc->get('company_country'))
                            {{ trim(($svc->get('company_city') ? $svc->get('company_city').', ' : '').$svc->get('company_country') ?? '') }}
                        @else
                            No address set
                        @endif
                    </p>

                    <div class="mt-3">
                        <label for="company_logo" class="btn btn-sm btn-outline-primary" style="cursor:pointer;">
                            <i class="fas fa-camera mr-1"></i> Change Logo
                        </label>
                        <input type="file" name="company_logo" id="company_logo" class="d-none" accept="image/*">
                        <input type="hidden" name="remove_logo" id="remove_logo" value="0">
                        @if($svc->get('company_logo'))
                            <button type="button" class="btn btn-sm btn-outline-danger" id="remove-logo-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card card-info card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Quick Info</h3></div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span><i class="fas fa-globe text-muted mr-2"></i> Country</span>
                            <span class="text-muted" id="country-badge">{{ $svc->get('company_country') ?: '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span><i class="fas fa-phone text-muted mr-2"></i> Phone</span>
                            <span class="text-muted" id="phone-badge">{{ $svc->get('company_phone') ?: '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span><i class="fas fa-envelope text-muted mr-2"></i> Email</span>
                            <span class="text-muted" id="email-badge">{{ $svc->get('company_email') ?: '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span><i class="fas fa-server text-muted mr-2"></i> SMTP</span>
                            @if($svc->get('company_smtp_host'))
                                <span class="badge badge-success">Configured</span>
                            @else
                                <span class="badge badge-secondary">Not Set</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="col-lg-8">
            <!-- Tabs -->
            <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab">
                        <i class="fas fa-building mr-1"></i> Company Info
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab">
                        <i class="fas fa-address-card mr-1"></i> Contact Details
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="smtp-tab" data-toggle="tab" href="#smtp" role="tab">
                        <i class="fas fa-envelope mr-1"></i> Email / SMTP
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="branding-tab" data-toggle="tab" href="#branding" role="tab">
                        <i class="fas fa-palette mr-1"></i> Branding
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="settingsTabContent">
                <!-- Company Info Tab -->
                <div class="tab-pane fade show active" id="info" role="tabpanel">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="company_name">
                                    <i class="fas fa-building text-primary mr-1"></i> Company Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="company_name" name="company_name"
                                       class="form-control form-control-lg @error('company_name') is-invalid @enderror"
                                       value="{{ old('company_name', $svc->get('company_name')) }}" required
                                       placeholder="e.g. Djagada Enterprises Ltd">
                                <small class="text-muted">This appears on invoices, quotes, and documents.</small>
                                @error('company_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="company_registration">
                                        <i class="fas fa-id-card text-primary mr-1"></i> Registration No.
                                    </label>
                                    <input type="text" id="company_registration" name="company_registration"
                                           class="form-control @error('company_registration') is-invalid @enderror"
                                           value="{{ old('company_registration', $svc->get('company_registration')) }}"
                                           placeholder="e.g. 2024-XXXXX">
                                    <small class="text-muted">Business registration or license number.</small>
                                    @error('company_registration')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="company_tax_id">
                                        <i class="fas fa-percent text-primary mr-1"></i> Tax ID / VAT No.
                                    </label>
                                    <input type="text" id="company_tax_id" name="company_tax_id"
                                           class="form-control @error('company_tax_id') is-invalid @enderror"
                                           value="{{ old('company_tax_id', $svc->get('company_tax_id')) }}"
                                           placeholder="e.g. VAT-123456">
                                    <small class="text-muted">Used on invoices and tax documents.</small>
                                    @error('company_tax_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="company_website">
                                    <i class="fas fa-globe text-primary mr-1"></i> Website
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-link"></i></span>
                                    </div>
                                    <input type="url" id="company_website" name="company_website"
                                           class="form-control @error('company_website') is-invalid @enderror"
                                           value="{{ old('company_website', $svc->get('company_website')) }}"
                                           placeholder="https://www.example.com">
                                    @error('company_website')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="company_industry">
                                    <i class="fas fa-industry text-primary mr-1"></i> Industry / Sector
                                </label>
                                <select id="company_industry" name="company_industry" class="form-control">
                                    <option value="">-- Select Industry --</option>
                                    @foreach(['Technology', 'Manufacturing', 'Trading', 'Retail', 'Construction', 'Healthcare', 'Education', 'Hospitality', 'Agriculture', 'Transportation', 'Real Estate', 'Other'] as $ind)
                                        <option value="{{ $ind }}" {{ $svc->get('company_industry') === $ind ? 'selected' : '' }}>{{ $ind }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Details Tab -->
                <div class="tab-pane fade" id="contact" role="tabpanel">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="company_email">
                                        <i class="fas fa-envelope text-primary mr-1"></i> Business Email
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">@</span>
                                        </div>
                                        <input type="email" id="company_email" name="company_email"
                                               class="form-control @error('company_email') is-invalid @enderror"
                                               value="{{ old('company_email', $svc->get('company_email')) }}"
                                               placeholder="info@yourcompany.com">
                                        @error('company_email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="company_phone">
                                        <i class="fas fa-phone text-primary mr-1"></i> Phone Number
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                        </div>
                                        <input type="tel" id="company_phone" name="company_phone"
                                               class="form-control @error('company_phone') is-invalid @enderror"
                                               value="{{ old('company_phone', $svc->get('company_phone')) }}"
                                               placeholder="+1 (268) 555-0100">
                                        @error('company_phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="company_address">
                                    <i class="fas fa-map-marker-alt text-primary mr-1"></i> Street Address
                                </label>
                                <input type="text" id="company_address" name="company_address"
                                       class="form-control @error('company_address') is-invalid @enderror"
                                       value="{{ old('company_address', $svc->get('company_address')) }}"
                                       placeholder="123 Main Street, Suite 100">
                                @error('company_address')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="company_city">
                                        <i class="fas fa-city text-primary mr-1"></i> City
                                    </label>
                                    <input type="text" id="company_city" name="company_city"
                                           class="form-control @error('company_city') is-invalid @enderror"
                                           value="{{ old('company_city', $svc->get('company_city')) }}"
                                           placeholder="St. John's">
                                    @error('company_city')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="company_state">
                                        <i class="fas fa-map text-primary mr-1"></i> State / Province
                                    </label>
                                    <input type="text" id="company_state" name="company_state"
                                           class="form-control @error('company_state') is-invalid @enderror"
                                           value="{{ old('company_state', $svc->get('company_state')) }}"
                                           placeholder="Saint George">
                                    @error('company_state')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="company_postal">
                                        <i class="fas fa-mail-bulk text-primary mr-1"></i> Postal Code
                                    </label>
                                    <input type="text" id="company_postal" name="company_postal"
                                           class="form-control @error('company_postal') is-invalid @enderror"
                                           value="{{ old('company_postal', $svc->get('company_postal')) }}"
                                           placeholder="00000">
                                    @error('company_postal')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="company_country">
                                    <i class="fas fa-globe-americas text-primary mr-1"></i> Country
                                </label>
                                <select id="company_country" name="company_country" class="form-control select2">
                                    <option value="">-- Select Country --</option>
                                    @foreach(['Afghanistan','Albania','Algeria','Angola','Antigua and Barbuda','Argentina','Armenia','Australia','Austria','Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','Brunei','Bulgaria','Burkina Faso','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Central African Republic','Chad','Chile','China','Colombia','Comoros','Congo','Costa Rica','Croatia','Cuba','Cyprus','Czech Republic','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Ethiopia','Fiji','Finland','France','Gabon','Gambia','Georgia','Germany','Ghana','Greece','Grenada','Guatemala','Guinea','Guinea-Bissau','Guyana','Haiti','Honduras','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy','Jamaica','Japan','Jordan','Kazakhstan','Kenya','Kiribati','Kosovo','Kuwait','Kyrgyzstan','Laos','Latvia','Lebanon','Liberia','Libya','Lithuania','Luxembourg','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Mauritania','Mauritius','Mexico','Micronesia','Moldova','Monaco','Mongolia','Montenegro','Morocco','Mozambique','Myanmar','Namibia','Nauru','Nepal','Netherlands','New Zealand','Nicaragua','Niger','Nigeria','North Korea','North Macedonia','Norway','Oman','Pakistan','Palau','Palestine','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Qatar','Romania','Russia','Rwanda','Saint Kitts and Nevis','Saint Lucia','Saint Vincent and the Grenadines','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Korea','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Sweden','Switzerland','Syria','Taiwan','Tajikistan','Tanzania','Thailand','Timor-Leste','Togo','Tonga','Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Yemen','Zambia','Zimbabwe'] as $c)
                                        <option value="{{ $c }}" {{ $svc->get('company_country') === $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                                @error('company_country')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SMTP Tab -->
                <div class="tab-pane fade" id="smtp" role="tabpanel">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Configure SMTP settings for outgoing emails (invoices, quotes, notifications).
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="company_smtp_host">
                                        <i class="fas fa-server text-primary mr-1"></i> SMTP Host
                                    </label>
                                    <input type="text" id="company_smtp_host" name="company_smtp_host"
                                           class="form-control @error('company_smtp_host') is-invalid @enderror"
                                           value="{{ old('company_smtp_host', $svc->get('company_smtp_host')) }}"
                                           placeholder="smtp.gmail.com">
                                    @error('company_smtp_host')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="company_smtp_port">
                                        <i class="fas fa-plug text-primary mr-1"></i> Port
                                    </label>
                                    <input type="text" id="company_smtp_port" name="company_smtp_port"
                                           class="form-control @error('company_smtp_port') is-invalid @enderror"
                                           value="{{ old('company_smtp_port', $svc->get('company_smtp_port')) }}"
                                           placeholder="587">
                                    @error('company_smtp_port')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="company_smtp_username">
                                        <i class="fas fa-user text-primary mr-1"></i> Username
                                    </label>
                                    <input type="text" id="company_smtp_username" name="company_smtp_username"
                                           class="form-control @error('company_smtp_username') is-invalid @enderror"
                                           value="{{ old('company_smtp_username', $svc->get('company_smtp_username')) }}"
                                           placeholder="your-email@gmail.com">
                                    @error('company_smtp_username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="company_smtp_password">
                                        <i class="fas fa-lock text-primary mr-1"></i> Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="company_smtp_password" name="company_smtp_password"
                                               class="form-control @error('company_smtp_password') is-invalid @enderror"
                                               value="" placeholder="Enter password">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="company_smtp_password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('company_smtp_password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="company_smtp_from_name">
                                        <i class="fas fa-signature text-primary mr-1"></i> From Name
                                    </label>
                                    <input type="text" id="company_smtp_from_name" name="company_smtp_from_name"
                                           class="form-control"
                                           value="{{ old('company_smtp_from_name', $svc->get('company_smtp_from_name')) }}"
                                           placeholder="Djagada ERP">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="company_smtp_from_email">
                                        <i class="fas fa-paper-plane text-primary mr-1"></i> From Email
                                    </label>
                                    <input type="email" id="company_smtp_from_email" name="company_smtp_from_email"
                                           class="form-control"
                                           value="{{ old('company_smtp_from_email', $svc->get('company_smtp_from_email')) }}"
                                           placeholder="noreply@yourcompany.com">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="company_smtp_encryption" value="tls"
                                           {{ $svc->get('company_smtp_encryption') !== 'none' ? 'checked' : '' }}>
                                    Enable TLS Encryption
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Branding Tab -->
                <div class="tab-pane fade" id="branding" role="tabpanel">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="show_logo_on_docs" value="1"
                                           {{ $svc->get('show_logo_on_docs') ? 'checked' : '' }}>
                                    Show company logo on invoices, quotes, and documents
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="company_footer_text">
                                    <i class="fas fa-file-alt text-primary mr-1"></i> Document Footer Text
                                </label>
                                <textarea id="company_footer_text" name="company_footer_text"
                                          class="form-control" rows="3"
                                          placeholder="Thank you for your business!">{{ old('company_footer_text', $svc->get('company_footer_text')) }}</textarea>
                                <small class="text-muted">Appears at the bottom of invoices and quotes.</small>
                            </div>

                            <div class="form-group">
                                <label for="company_notes">
                                    <i class="fas fa-sticky-note text-primary mr-1"></i> Default Invoice Notes
                                </label>
                                <textarea id="company_notes" name="company_notes"
                                          class="form-control" rows="3"
                                          placeholder="Payment terms: Net 30 days...">{{ old('company_notes', $svc->get('company_notes')) }}</textarea>
                                <small class="text-muted">Auto-filled when creating new invoices.</small>
                            </div>

                            <div class="form-group">
                                <label for="company_terms">
                                    <i class="fas fa-file-contract text-primary mr-1"></i> Default Terms & Conditions
                                </label>
                                <textarea id="company_terms" name="company_terms"
                                          class="form-control" rows="3"
                                          placeholder="Terms and conditions...">{{ old('company_terms', $svc->get('company_terms')) }}</textarea>
                                <small class="text-muted">Auto-filled when creating new invoices/quotes.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="card card-outline">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted"><i class="fas fa-clock mr-1"></i> Last saved: {{ $svc->get('updated_at') ? now()->diffForHumans($svc->get('updated_at')) : 'Never' }}</small>
                    </div>
                    <div>
                        <a href="{{ route('admin.settings.company') }}" class="btn btn-default">
                            <i class="fas fa-undo mr-1"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-primary" id="save-btn">
                            <i class="fas fa-save mr-1"></i> Save All Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
<style>
    .nav-tabs .nav-link.active { font-weight: 600; border-bottom-color: #007bff; }
    .nav-tabs .nav-link { color: #495057; font-weight: 500; }
    .nav-tabs .nav-link:hover { border-color: #e9ecef #e9ecef #dee2e6; }
    #save-btn:disabled { cursor: not-allowed; opacity: 0.7; }
    .form-control-lg { font-size: 1.1rem; }
    .img-circle { border-radius: 12px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
$(function() {
    // Select2 for country
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: '-- Select Country --',
        allowClear: true,
        width: '100%'
    });

    // Live preview of company name
    $('#company_name').on('input', function() {
        const v = $(this).val();
        $('#name-display').text(v || 'Your Company');
        $('.brand-text').first().text(v || 'Djagada ERP');
    });

    // Live preview of email
    $('#company_email').on('input', function() {
        const v = $(this).val();
        $('#email-display').text(v || 'No email set');
        $('#email-badge').text(v || '-');
    });

    // Live preview of phone
    $('#company_phone').on('input', function() {
        $('#phone-badge').text($(this).val() || '-');
    });

    // Live preview of city/country
    $('#company_city, #company_country').on('change input', function() {
        const city = $('#company_city').val();
        const country = $('#company_country').val();
        const display = (city ? city + ', ' : '') + country;
        $('#city-display').text(display || 'No address set');
        $('#country-badge').text(country || '-');
    });

    // Logo preview
    $('#company_logo').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                $('#logo-placeholder').addClass('d-none');
                $('#logo-preview').removeClass('d-none').attr('src', ev.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove logo
    $('#remove-logo-btn').on('click', function() {
        if (confirm('Remove company logo?')) {
            $('#remove_logo').val('1');
            $('#logo-preview').addClass('d-none');
            $('#logo-placeholder').removeClass('d-none');
        }
    });

    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        const target = $(this).data('target');
        const input = $('#' + target);
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Form submit
    $('#company-form').on('submit', function() {
        $('#save-btn').html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...').prop('disabled', true);
    });

    // Tab persistence
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        localStorage.setItem('settingsTab', e.target.id);
    });
    const savedTab = localStorage.getItem('settingsTab');
    if (savedTab) {
        $('[data-toggle="tab"]').filter('#' + savedTab.replace('-tab', '')).tab('show');
    }
});
</script>
@endpush
