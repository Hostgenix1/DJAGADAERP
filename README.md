<p align="center"><a href="https://github.com/Hostgenix1/DJAGADAERP"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h1 align="center">DJAGADA ERP</h1>

<p align="center">
  Enterprise Resource Planning system for import/export trading businesses
</p>

<p align="center">
  <a href="https://github.com/Hostgenix1/DJAGADAERP/releases"><img src="https://img.shields.io/badge/version-v2.4.0-blue" alt="Version"></a>
  <a href="https://github.com/Hostgenix1/DJAGADAERP/blob/main/LICENSE"><img src="https://img.shields.io/badge/license-MIT-green" alt="License"></a>
  <a href="https://github.com/Hostgenix1/DJAGADAERP"><img src="https://img.shields.io/badge/Laravel-13.x-red" alt="Laravel"></a>
  <a href="https://github.com/Hostgenix1/DJAGADAERP"><img src="https://img.shields.io/badge/PHP-8.3-purple" alt="PHP"></a>
</p>

---

## Features

### Auth & RBAC
- Login (Laravel Breeze); self-registration is disabled — administrators create users
- Role-based access control (Super Admin, Staff, Sales)
- Granular permissions per module (view/create/update/delete)
- Activity audit log (partial: repository CRUD, settings, users, roles)

### Dashboard
- Revenue, customers, outstanding balances, stock and P&L KPI cards
- Monthly revenue and cash-flow charts (Chart.js)
- Lead pipeline doughnut chart
- Top customers, low-stock products, recent activity

### CRM
- **Customers** — company profiles, contacts, timeline, document uploads, quick actions
- **Contacts** — multi-contact per customer
- **Leads** — status pipeline (new/contacted/qualified/proposal/won/lost)
- **Follow-ups** — scheduled tasks with due dates and completion tracking
- **Communications** — call/WhatsApp/email/meeting logs

### Products & Pricing
- **Brands**, **Categories**, **Suppliers**, **Products** — SKU, buy/sell price, currency, tax, pack qty, dimensions, specifications, certificates, images
- **Supplier Prices** — supplier cost per packaging/incoterm with validity
- **Selling Prices** — approved selling prices, customer-specific overrides, AI approval flag
- **AI Pricing API** — read-only Sanctum endpoint returning only approved, valid selling prices (never costs or margins)

### Sales
- **Quotations** — line items, tax/discount, revisions, convert to proforma or commercial invoice
- **Invoices** — commercial, proforma, credit note, packing list, delivery note; auto-numbering; PDF with logo, QR, payment details; payment tracking
- **Orders** — sales orders with payment terms and status flow
- **Payments** — customer and supplier payments, allocation to invoices/bills, outstanding balances
- **Shipments** — container and logistics tracking with PDF

### Purchasing & Inventory
- **Purchase Orders** — supplier POs with line items and PDF
- **Supplier Bills** — bill entry and payment allocation
- **Warehouses** — multiple locations
- **Stock** — stock in/out/adjustment with negative-stock protection, valuation, low-stock alerts

### Finance
- **Expenses** — categorized operating expenses
- **Reports** — sales, financial P&L, customers, suppliers, inventory, employees; Excel/CSV/PDF export
- **Multi-currency** — exchange rates and base-currency conversion

### HR & Payroll
- **Employees** — profiles, documents, contract data, IBAN
- **Attendance** and **Leaves**
- **Payroll** — attendance-based auto-fill, allowances, overtime, deductions, approval and mark-paid
- **Payslips** — one-page A4 PDF, masked IBAN/Emirates ID, amount in words, WPS fields, email delivery
- **My Payslips** — employees can download their own payslips

### Settings
- Company profile (logo, address, TRN), SMTP, branding, taxes, currencies, payment terms, units, audit log

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13.23, PHP 8.3 |
| Database | MySQL 8.4 |
| Frontend | AdminLTE 3.2, Bootstrap 4.6, jQuery 3.7 (CDN, no build step) |
| Charts | Chart.js 3.9 |
| Tables | Yajra DataTables 13.x (server-side) |
| PDF | barryvdh/laravel-dompdf 3.x, simplesoftwareio/simple-qrcode |
| Auth | Laravel Breeze (Blade), spatie/laravel-permission 8.x |
| Audit | spatie/laravel-activitylog 4.x |
| Excel | maatwebsite/excel 3.x (report exports) |
| API | Laravel Sanctum (AI pricing endpoint) |

---

## Installation

```bash
# Clone the repo
git clone https://github.com/Hostgenix1/DJAGADAERP.git
cd DJAGADAERP

# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database (MySQL must be running)
# Edit .env with your DB credentials
php artisan migrate --force

# Seed roles, permissions, currencies, taxes, and the admin account
php artisan db:seed --class=DatabaseSeeder

# Optional: demo customers, products, invoices, etc.
php artisan db:seed --class=DemoDataSeeder

# Storage link (for uploaded logos/documents)
php artisan storage:link

# Start dev server
php artisan serve
```

Visit: http://127.0.0.1:8000

### Scheduled tasks

Payroll due-date transitions and price expiry run from the scheduler. Add this cron entry on the server:

```
* * * * * cd /path/to/DJAGADAERP && php artisan schedule:run >> /dev/null 2>&1
```

---

## Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@djagada.com | password |

Change the password immediately after the first login. `DemoDataSeeder` is optional and creates sample business data only.

---

## Testing & Code Style

```bash
php artisan test          # Feature + unit tests (SQLite in-memory)
./vendor/bin/pint --test  # Check formatting
./vendor/bin/pint         # Apply formatting
```

The test suite covers auth, profile, invoice/payment flow, and payroll calculations. `pint.json` excludes release-package folders from formatting.

---

## Module Generator

Scaffold new CRUD modules with a single command:

```bash
# Create a manifest in database/modules/yourmodule.php, then:
php artisan make:module yourmodule --migrate
```

Generates: Migration, Model, Repository, Service, Controller, FormRequests, Routes, Views, Permissions, Menu entry.

---

## Project Structure

```
app/
├── Console/Commands/MakeModule.php      # Module generator
├── Contracts/Repositories/              # Repository interfaces
├── Http/Controllers/                    # All controllers
├── Models/                              # Eloquent models
├── Repositories/                        # Repository pattern (with audit)
├── Services/                            # Business logic layer
├── Support/                             # Currency, VAT QR, masking, money helpers
config/
├── menu.php                             # Sidebar menu (RBAC-gated)
├── permissions.php                      # Permission definitions
database/
├── modules/                             # Module schema manifests
├── seeders/                             # Demo + role seeders
docs/                                    # User manual and screenshots
resources/views/
├── layouts/                             # AdminLTE app + guest layouts
├── {module}/                            # Per-module views
routes/modules/                          # Per-module routes (auto-loaded)
```

---

## License

The DJAGADA ERP is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
