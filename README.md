<p align="center"><a href="https://github.com/Hostgenix1/DJAGADAERP"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h1 align="center">DJAGADA ERP</h1>

<p align="center">
  Enterprise Resource Planning system for import/export trading businesses
</p>

<p align="center">
  <a href="https://github.com/Hostgenix1/DJAGADAERP/releases"><img src="https://img.shields.io/badge/version-v1.0.0-blue" alt="Version"></a>
  <a href="https://github.com/Hostgenix1/DJAGADAERP/blob/main/LICENSE"><img src="https://img.shields.io/badge/license-MIT-green" alt="License"></a>
  <a href="https://github.com/Hostgenix1/DJAGADAERP"><img src="https://img.shields.io/badge/Laravel-13.x-red" alt="Laravel"></a>
  <a href="https://github.com/Hostgenix1/DJAGADAERP"><img src="https://img.shields.io/badge/PHP-8.3-purple" alt="PHP"></a>
</p>

---

## Features

### Auth & RBAC
- Login / Register (Laravel Breeze)
- Role-based access control (Super Admin, Staff, Sales)
- Granular permissions per module (view/create/update/delete)
- Activity audit log

### Dashboard
- Revenue, customers, outstanding balances KPI cards
- Monthly revenue line chart (Chart.js)
- Lead pipeline doughnut chart
- Top customers & recent activity feed

### CRM
- **Customers** — company profiles, contacts, timeline, document uploads, quick actions
- **Contacts** — multi-contact per customer (name, email, phone, position)
- **Leads** — status pipeline (new/contacted/qualified/proposal/won/lost), expected amounts
- **Follow-ups** — scheduled tasks with due dates and completion tracking
- **Communications** — call/whatsapp/email/meeting logs with direction and contact

### Products
- **Brands** — brand management
- **Categories** — product categories
- **Suppliers** — supplier profiles with payment terms
- **Products** — SKU, buy/sell price, currency, tax, pack qty/type, dimensions, specs, certifications, images

### Quotations
- Dynamic line items with product select and auto-calculation
- Subtotal / tax / discount / grand total
- Status tracking (draft/sent/accepted/rejected/expired)
- Revision support
- Convert to Proforma Invoice or Commercial Invoice

### Invoices
- Dynamic line items with product select and auto-calculation
- Types: Commercial, Proforma, Credit Note, Packing List, Delivery Note
- Auto-numbering (INV-00001, PI-00001, etc.)
- Modern PDF with company logo, QR code, status badge, payment details
- Payment tracking with balance calculation

### Payments
- Record customer or supplier payments
- Allocate payments to specific invoices
- Outstanding balances view
- Payment methods: cash, bank, cheque, mobile, transfer

### Documents
- Versioned file uploads with categories
- Polymorphic — attach to customers, invoices, payments
- Global search across all documents
- Embeddable upload partial on any entity show page

### Settings
- **Company Profile** — logo, name, address, website, industry, tax ID (tabbed UI with live preview)
- **SMTP / Email** — host, port, username, password, encryption, from address
- **Branding** — logo on docs toggle, footer text, default notes & terms
- **Taxes** — CRUD with rate, type (sales/purchase), active status
- **Currency** — multi-currency support with exchange rates
- **Audit Log** — all user actions tracked with spatie/activitylog

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13.23, PHP 8.3 |
| Database | MySQL 8.4 |
| Frontend | AdminLTE 3.2, Bootstrap 4.6, jQuery 3.7 |
| Charts | Chart.js 3.9 |
| Tables | Yajra DataTables 13.x (server-side) |
| PDF | barryvdh/laravel-dompdf 3.x, simplesoftwareio/simple-qrcode |
| Auth | Laravel Breeze (Blade), spatie/laravel-permission 8.x |
| Audit | spatie/laravel-activitylog 4.x |
| Excel | maatwebsite/excel 3.x (available, not yet wired) |
| API | Laravel Sanctum (available, not yet wired) |
| Assets | CDN (no npm/webpack required) |

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

# Seed roles, permissions, and demo data
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=DemoDataSeeder

# Storage link (for uploaded logos/documents)
php artisan storage:link

# Start dev server
php artisan serve
```

Visit: http://127.0.0.1:8000

---

## Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@djagada.com | password |
| Sales | alfa@djagada.com | secret123 |

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
config/
├── menu.php                             # Sidebar menu (RBAC-gated)
├── permissions.php                      # Permission definitions
database/
├── modules/                             # Module schema manifests
├── seeders/                             # Demo + role seeders
resources/views/
├── layouts/                             # AdminLTE app + guest layouts
├── {module}/                            # Per-module views
routes/modules/                          # Per-module routes (auto-loaded)
```

---

## License

The DJAGADA ERP is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
