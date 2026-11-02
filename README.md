<p align="center"><a href="https://github.com/Hostgenix1/DJAGADAERP"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h1 align="center">DJAGADA ERP</h1>

<p align="center">
  Enterprise Resource Planning system for import/export trading businesses
</p>

<p align="center">
  <a href="https://github.com/Hostgenix1/DJAGADAERP/releases"><img src="https://img.shields.io/badge/version-v2.5.0-blue" alt="Version"></a>
  <a href="https://github.com/Hostgenix1/DJAGADAERP/blob/main/LICENSE"><img src="https://img.shields.io/badge/license-MIT-green" alt="License"></a>
  <a href="https://github.com/Hostgenix1/DJAGADAERP"><img src="https://img.shields.io/badge/Laravel-13.x-red" alt="Laravel"></a>
  <a href="https://github.com/Hostgenix1/DJAGADAERP"><img src="https://img.shields.io/badge/PHP-8.3-purple" alt="PHP"></a>
</p>

---

## Features

### Auth & RBAC
- Login (Laravel Breeze); self-registration is disabled — administrators create users
- Role presets: Super Admin, CEO, Sales, Finance, HR, Warehouse, Logistics, Viewer, Staff, Employee (Self-Service)
- Granular permissions per module (view/create/update/delete/approve)
- Activity audit log (partial: repository CRUD, settings, users, roles, key document actions)

### Dashboard
- Revenue, customers, outstanding balances, stock and P&L KPI cards
- Monthly revenue and cash-flow charts (Chart.js)
- Lead pipeline doughnut chart
- Calendar of follow-ups, invoice due dates, shipment ETAs and purchase-request need-by dates (FullCalendar)
- Top customers, low-stock products, recent activity

### CRM
- **Customers** — company profiles, contacts, timeline, document uploads, quick actions
- **Contacts** — multi-contact per customer
- **Leads** — status pipeline (new/contacted/qualified/proposal/won/lost)
- **Follow-ups** — scheduled tasks with due dates and completion tracking
- **Communications** — call/WhatsApp/email/meeting logs

### Products & Pricing
- **Brands**, **Categories**, **Suppliers**, **Products** — SKU, buy/sell price, currency, tax, pack qty, dimensions, specifications, certificates
- **Product images** — multi-upload, primary image selection, delete from the product page
- **Supplier Prices** — supplier cost per packaging/incoterm with validity
- **Selling Prices** — approved selling prices, customer-specific overrides, AI approval flag
- **AI Pricing API** — read-only Sanctum endpoint returning only approved, valid selling prices (never costs or margins)

### Sales
- **Quotations** — line items, tax/discount, revisions, PDF, email with PDF attachment, convert to proforma or commercial invoice
- **Invoices** — commercial, proforma, credit note, packing list, delivery note; auto-numbering; PDF with logo, QR, payment details; payment tracking
- **Orders** — sales orders with payment terms and status flow
- **Payments** — customer and supplier payments, allocation to invoices/bills, outstanding balances
- **Shipments** — ETD/ETA, carriers, container count/type/numbers, Bill of Lading number, document uploads and PDF

### Purchasing & Inventory
- **Purchase Requests** — internal requests with items, submit/approve/reject workflow and conversion to a purchase order
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
- Company profile (logo, signature, address, TRN), branding, taxes, currencies, payment terms, units, audit log
- **SMTP settings are applied at runtime** — no `.env` edit needed once saved in the admin panel

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

The test suite covers auth, profile, invoice/payment flow, documents, payroll calculations, purchase-request approval/conversion, quotation email, product images, the dashboard calendar, role presets and SMTP configuration. `pint.json` excludes release-package folders from formatting.

---

## Roles

| Role | Scope |
|------|-------|
| Super Admin | Full access, users, roles and settings |
| CEO | Read-only across the business, including reports |
| Sales | CRM, quotations, invoices, payments, documents |
| Finance | Invoices, payments, expenses, supplier bills, currencies, reports |
| HR | Employees, attendance, leaves, payroll and payslips |
| Warehouse | Products, stock, warehouses, purchase requests and orders |
| Logistics | Shipments, orders, customers, documents |
| Viewer | Read-only business data without admin, payroll or settings |
| Staff | Read-only access to all modules |
| Employee (Self-Service) | Own payslips |

Permissions are managed under **User Management → Roles** after seeding.

---

## Email / SMTP

Configure the mail server under **Settings → Company → Email / SMTP**. Saved settings override `.env` at runtime, so no deployment change is needed after saving. The SMTP password is kept when the field is submitted blank.

Verify delivery with:

```bash
php artisan mail:test you@example.com
```

If no SMTP host is saved, Laravel falls back to `MAIL_MAILER` in `.env`.

---

## Database snapshots

Create a MySQL dump for delivery or backup:

```bash
php artisan db:snapshot
php artisan db:snapshot --gzip --path=storage/app/backups
```

`mysqldump` must be available on the server, or point to it explicitly with `DB_DUMP_BINARY` in `.env` or `--binary=`.

---

## Production checklist

- Set `APP_ENV=production` and `APP_DEBUG=false`
- Run `php artisan migrate --force`
- Seed once with `php artisan db:seed --class=DatabaseSeeder`
- Run `php artisan storage:link` for logos, signatures and product images
- Cache the framework: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- Run the scheduler (`schedule:run`) and a queue worker (`queue:work`)
- Serve over HTTPS and change the default admin password
- Take a `db:snapshot` before upgrades

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
