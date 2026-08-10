# DJAGADA ERP — Complete User Manual

> **Version:** 1.0.0 | **Platform:** Web (AdminLTE 3 + Bootstrap 4) | **App URL:** http://127.0.0.1:8000

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [Login & Roles](#2-login--roles)
3. [Flow Diagrams](#3-flow-diagrams)
4. [Dashboard](#4-dashboard)
5. [Menu-by-Menu Guide](#5-menu-by-menu-guide)
   - 5.1 CRM (Customer, Contact, Lead, Follow-up, Communication)
   - 5.2 Products (Brand, Category, Supplier, Product)
   - 5.3 Quotations
   - 5.4 Invoicing
   - 5.5 Orders
   - 5.6 Shipments
   - 5.7 Payments
   - 5.8 Documents
   - 5.9 Settings (Bank Accounts, Currency, Company, Taxes, Audit Log)
   - 5.10 User Management (Users, Roles)
6. [CRUD Buttons Reference](#6-crud-buttons-reference)
7. [Status Workflows](#7-status-workflows)
8. [Currency System](#8-currency-system)
9. [Role & Permission Matrix](#9-role--permission-matrix)
10. [Troubleshooting & Bug Fixes](#10-troubleshooting--bug-fixes)

---

## 1. System Overview

DJAGADA ERP is an ERP built for an import/export trading company. A single panel lets you run the entire sales-to-delivery chain:

**Lead (potential customer) → Customer → Quote → Order → Invoice → Payment → Shipment → Document**

There is **no separate "Institute Panel" or "Teacher Panel"** — this is a single-panel ERP. The sidebar menus you see depend only on your **role's permissions**. The roles are: **Super Admin**, **Staff**, **Sales** (see Section 9).

### Key Features
| Feature | Detail |
|---|---|
| Multi-currency | All prices are stored in the base currency and auto-convert to the selected currency |
| VAT modes | Excluded / Included / None — on both invoices and quotes |
| Line items | Dynamic rows, product auto-fill, unit select, sub-description |
| PDF invoice/quote | Professional navy-design PDF with QR code, bank details, and amount-in-words (EN/FR) |
| Quote → Invoice | One-click conversion |
| Audit log | Full history of every create/update/delete |
| Permissions | Separate view/create/update/delete rights per module and role |

### Default Login (from seeder)
| Email | Password | Role |
|---|---|---|
| `admin@djagada.com` | `password` | Super Admin (all permissions) |

---

## 2. Login & Roles

### How to Log In
1. Open `http://127.0.0.1:8000/login` in your browser.
2. Enter your email and password, then click **Sign In**.

![Login Page](screenshots/login.png)

### Roles — What Each Role Can Do
| Role | Capabilities |
|---|---|
| **Super Admin** | Everything — full CRUD, Settings, Users, Roles, Currencies, delete |
| **Staff** | **View only** (read-only) — can see records but cannot create/update/delete |
| **Sales** | Can create/update CRM, Quotes, Invoices, Payments, and Documents; cannot delete or access settings |

> **Note:** If a new user self-registers without a role, their sidebar will appear empty — an admin must assign a role via **User Management → Users → Edit**.

### Logout
Click your name in the top-right corner → **Sign out**.

---

## 3. Flow Diagrams

### 3.1 Complete Business Flow

![Business Flow](screenshots/diagram-flow.png)

### 3.2 Quote Lifecycle

![Quote Lifecycle](screenshots/diagram-quote.png)

### 3.3 Invoice Lifecycle

![Invoice Lifecycle](screenshots/diagram-invoice.png)

### 3.4 Order & Shipment Flow

![Order Flow](screenshots/diagram-order.png)

---

## 4. Dashboard

The Dashboard opens right after login. It shows:

- **KPI cards** — counts of Customers, Leads, Quotes, Orders, and Revenue
- **Monthly Revenue chart** — sales by month
- **Pipeline doughnut** — quote/order stages
- **Top Customers** — customers with the highest sales
- **Activity feed** — recent activity from the audit log

![Dashboard](screenshots/dashboard.png)

---

## 5. Menu-by-Menu Guide

> Sidebar structure: each menu group, its items, and what each item does.

### 5.1 CRM

#### Customer
- **What it is:** Your business partners (buyers).
- **`+ New` button:** Creates a new customer — company name, contact, address, currency, tax, notes.
- **Index table:** Each row has buttons — 👁 View, ✏ Edit, 🗑 Delete.
- **View page (Customer Profile):** Full detail plus tabs — Contacts, Leads, Quotes, Invoices, Payments, Documents, Communications. You can also add a new communication right from here.

| Screenshot | Page |
|---|---|
| ![Customers List](screenshots/customers.png) | Customers list (index) |
| ![Customer Create](screenshots/customer-create.png) | Create new customer |
| ![Customer Show](screenshots/customer-show.png) | Customer profile (view) |

- **Effect of Delete:** The customer is permanently removed (check related records first).

#### Contact
- A person linked to a customer (name, phone, email, designation).
- CRUD: index list → **New Contact** → form (customer select, name, email, phone) → Save.
- Actions: ✏ Edit, 🗑 Delete.

#### Lead
- A potential customer who has shown interest but is not yet a customer.
- Create with: company, contact person, email/phone, source, status, value.
- On the Lead **View** page you can **convert the lead to a Customer**.

#### Follow-up
- A reminder/task to follow up with a lead or customer — date, note, done/pending.

#### Communication
- A log of conversations (email/call/meeting) with customers/leads — type, date, note.
- Can also be quickly added from the customer profile.

### 5.2 Products

| Menu | What it is | Used for |
|---|---|---|
| **Brand** | Product brand (name + description) | Reference list |
| **Category** | Product category (name + description) | Dropdown in product form |
| **Supplier** | The party supplying goods (company, contact, payment terms) | Purchase side |
| **Product** | The actual product (SKU, buy/sell price, unit, pack qty/type, weight, dimensions, brand, category, supplier, currency) | Autofill in quotes/invoices |

- In the **Product Create** form, enter prices in the **base currency** (e.g., USD). The sell price is what appears in quotes/invoices.
- On the **Product index**, prices are shown like `12.50 USD`, and the currency column shows which currency is the base.

![Products List](screenshots/products.png)
![Product Create](screenshots/product-create.png)
![Product Show](screenshots/product-show.png)

### 5.3 Quotations

- **`+ New` button:** New quotation — Customer, Date, Currency, Bank, VAT, Payment Terms, Shipment details, Line items.
- **Adding line items:** Click **Add Item** → select a product (price autofills and converts to the selected currency), then set qty, unit, tax %, discount % — totals calculate automatically.
- **Row actions:**
  - 👁 View — full detail, with **Convert to Invoice / PDF** buttons
  - ✏ Edit — only in `draft`/`sent` status
  - 🔄 Convert to Proforma — in `draft`
  - 📄 Convert to Invoice (commercial) — in `draft`/`sent`/`accepted`
  - 🗑 Delete — in `draft`/`rejected`
- **PDF button (View page):** downloads the professional navy-design PDF — bank details, QR code, amount in words.

![Quotes List](screenshots/quotes.png)
![Quote Create](screenshots/quote-create.png)
![Quote Show](screenshots/quote-show.png)

### 5.4 Invoicing

- Types: **Proforma**, **Commercial**, **Credit Note**, **Debit Note**.
- The create form is the standard AdminLTE form — Document Details, VAT & Payment Terms, Shipment Details, Line Items (boxed), Notes & Terms.
- **Row actions:**
  - 👁 View — detail page (items, VAT, shipment, bank, notes)
  - 📄 PDF — invoice PDF download
  - ✏ Edit — only in `draft`
  - 🗑 Delete — only in `draft`/`cancelled`
- **Receiving payment:** record a payment in the Payments module against the invoice → the invoice becomes `paid`/`partial`.

![Invoices List](screenshots/invoices.png)
![Invoice Create](screenshots/invoice-create.png)
![Invoice Show](screenshots/invoice-show.png)

### 5.5 Orders

- Create: Customer, Currency, line items (product, qty, price), totals.
- **Status update:** On the Order **View** page, change the status (draft → confirmed → in_transit → customs → delivered / cancelled / partial / returned).
- Actions: 👁 View, ✏ Edit (in draft), 🗑 Delete (in draft/cancelled).

![Orders List](screenshots/orders.png)

### 5.6 Shipments

- Create a shipment linked to an order — tracking no, carrier, ports, dates, status.
- Status: `preparing` → `in_transit` → `customs` → `delivered`.
- Actions: 👁 View, ✏ Edit (in preparing/in_transit/customs), 🗑 Delete (in preparing).

![Shipments List](screenshots/shipments.png)

### 5.7 Payments

- **Outstanding page** (`Payments → Outstanding`): lists invoices that are not fully paid — each with a **Pay** button.
- **New Payment:** type (customer/supplier), party, amount, method (cash/bank/transfer), date, reference, notes, and allocated invoices.
- Recording a payment updates the invoice status (partial/paid).
- Actions: 👁 View, 🏢 Customer/🚚 Supplier jump, 🗑 Delete.

![Payments List](screenshots/payments.png)
![Payments Outstanding](screenshots/payments-outstanding.png)

### 5.8 Documents

- **Upload Document button:** opens a modal — title, category, file (max 20MB), notes.
- Documents can be attached to any record (customer/quote/invoice/payment...) — choose the entity in the select.
- A search box filters the list by title/file.
- Actions: ⬇ Download, 🗑 Delete.

![Documents](screenshots/documents.png)

### 5.9 Settings — for Super Admin

#### Bank Accounts
- Company bank accounts — bank name, account name/number, IBAN, SWIFT, **bank address**, currency.
- This is the account printed in the **Bank Details** box on invoice/quote PDFs.

#### Currency ⭐
- Every currency has a **Rate (vs base)** — i.e., how many units of this currency equal 1 base currency.
- **Default column:** the currency with the ✅ Default badge is the **base currency** — all product prices are stored in it.
- ⚠️ **Changing the default currency:** editing the default in Settings automatically **rescales all rates**. Verify carefully before changing.
- CRUD: ✏ Edit, 🗑 Delete — but do not delete the default or any currency that is in use.

![Currencies](screenshots/currencies.png)

#### Company
4 tabs:
| Tab | What you enter | Effect |
|---|---|---|
| **Company Info** | Name, address, free zone, trade license, city/country, tax/TRN | Printed in the invoice/quote PDF header |
| **Contact Details** | Email, phone, website | Contact info on the PDF |
| **SMTP/Email** | SMTP host/port/credentials | For future email sending |
| **Branding** | Logo upload, footer text | PDF logo + footer |

![Settings Company](screenshots/settings-company.png)

#### Taxes
- A list of VAT rates — name + rate % (e.g., UAE VAT 5%).
- Create/Edit/Delete via modal — these feed the VAT dropdown on invoices/quotes.

![Settings Taxes](screenshots/settings-taxes.png)

#### Audit Log
- Every activity by every user — who created/changed/deleted what and when.
- Read-only table.

![Settings Audit](screenshots/settings-audit.png)

### 5.10 User Management — Super Admin

#### Users
- **New User:** name, email, password + **role assignment** (Super Admin / Staff / Sales).
- Edit: change role, reset password.
- Delete: remove the user.

![Users](screenshots/users.png)

#### Roles
- Create a role (e.g., "Accountant") and choose **permissions** (view/create/update/delete per module).
- Super Admin/Staff/Sales are pre-seeded; create new roles as needed.

![Roles](screenshots/roles.png)

---

## 6. CRUD Buttons Reference

### 6.1 Icon Glossary
| Icon | Button | What it does |
|---|---|---|
| 👁 `fa-eye` (green) | View | Opens the detail page |
| ✏ `fa-pen` (blue) | Edit | Opens the edit form (some modules restrict by status) |
| 🗑 `fa-trash` (red) | Delete | Deletes the record after confirmation |
| 📄 `fa-file-pdf` (red) | PDF | Downloads the invoice/quote PDF |
| 🔄 `fa-exchange-alt` (yellow) | Convert to Proforma | Converts the quote to a proforma invoice |
| 📄 `fa-file-invoice` (blue) | Convert to Invoice | Converts the quote to a commercial invoice |
| 🏢 `fa-building` (info) | Customer | Opens the payment's customer profile |
| 🚚 `fa-truck` (info) | Supplier | Opens the payment's supplier edit page |
| ⬇ `fa-download` | Download | Downloads an attached document |
| ➕ | New / Add / Upload | Creates a new record / item / document |

### 6.2 CRUD Matrix
| Module | List | Create | View | Edit | Delete | Extra |
|---|---|---|---|---|---|---|
| Customer | ✅ | ✅ | ✅ | ✅ | ✅ | Profile tabs + communications |
| Contact | ✅ | ✅ | ❌ | ✅ | ✅ | — |
| Lead | ✅ | ✅ | ✅ | ✅ | ✅ | Convert to customer |
| Follow-up | ✅ | ✅ | ❌ | ✅ | ✅ | — |
| Communication | ✅ | ✅ | ❌ | ✅ | ✅ | Quick add on customer profile |
| Brand | ✅ | ✅ | ❌ | ✅ | ✅ | — |
| Category | ✅ | ✅ | ❌ | ✅ | ✅ | — |
| Supplier | ✅ | ✅ | ❌ | ✅ | ✅ | — |
| Product | ✅ | ✅ | ✅ | ✅ | ✅ | Currency-coded prices |
| Quote | ✅ | ✅ | ✅ | ⚠️ draft/sent | ⚠️ draft/rejected | PDF, Convert to Proforma/Invoice |
| Invoice | ✅ | ✅ | ✅ | ⚠️ draft | ⚠️ draft/cancelled | PDF |
| Order | ✅ | ✅ | ✅ | ⚠️ draft | ⚠️ draft/cancelled | Status update |
| Shipment | ✅ | ✅ | ✅ | ⚠️ preparing/in_transit/customs | ⚠️ preparing | — |
| Payment | ✅ | ✅ | ✅ | ❌ | ✅ | Outstanding list |
| Document | ✅ (upload) | ✅ | ❌ | ❌ | ✅ | Search + download |
| Bank Account | ✅ | ✅ | ❌ | ✅ | ✅ | PDF bank box |
| Currency | ✅ | ✅ | ❌ | ✅ | ✅ | Default badge |
| Tax | ✅ (modal) | ✅ | ❌ | ✅ (modal) | ✅ (modal) | — |
| User | ✅ | ✅ | ❌ | ✅ | ✅ | Role assignment |
| Role | ✅ | ✅ | ❌ | ✅ | ✅ | Permission checkboxes |

### 6.3 Common Form Buttons
| Button | Effect |
|---|---|
| **Save** | Creates/updates the record → success message and return to the list |
| **Cancel** | Closes the form without saving |
| **Add Item** (Line items) | Adds a new row (product select, qty, unit, price...) |
| **Upload** (Documents modal) | Uploads and attaches the file |
| ❌ (line item row) | Removes that row and recalculates totals |

---

## 7. Status Workflows

### Quote Statuses
| Status | Edit | Convert | Delete |
|---|---|---|---|
| draft | ✅ | ✅ (Proforma + Invoice) | ✅ |
| sent | ✅ | ✅ (Invoice) | ❌ |
| accepted | ❌ | ✅ (Invoice) | ❌ |
| rejected | ❌ | ❌ | ✅ |

### Invoice Statuses
| Status | Edit | Delete | Payments |
|---|---|---|---|
| draft | ✅ | ✅ | — |
| sent | ❌ | ❌ | → partial/paid/overdue |
| partial / paid / overdue | ❌ | ❌ | receive payment to advance |

### Order Statuses
`draft → confirmed → in_transit → customs → delivered` | can also go to `partial` / `cancelled` / `returned`

### Shipment Statuses
`preparing → in_transit → customs → delivered`

---

## 8. Currency System

- **Base currency** = the currency with `is_default = true` (the Default badge). Currently **USD**.
- **Rates (demo):** USD = 1, AED = 3.6725, EUR = 0.92, XCD = 2.7 → meaning `1 USD = 3.6725 AED`.
- **Product prices** are always entered in the base currency.
- When creating a **quote/invoice**, select a currency — all product prices **auto-convert** to that currency (and are saved in that currency).
- **Bank account filter:** on the invoice form, changing the currency only shows bank accounts of that currency.
- **Changing the default currency:** all rates are rescaled; existing documents are not revalued — they keep the currency/rates they were created with.

---

## 9. Role & Permission Matrix

| Module | Super Admin | Staff (view only) | Sales |
|---|---|---|---|
| CRM (customer/contact/lead/follow-up/communication) | View+Create+Update+Delete | View | View+Create+Update |
| Products (brand/category/supplier/product) | View+Create+Update+Delete | View | View |
| Quotes | View+Create+Update+Delete | View | View+Create+Update |
| Invoices | View+Create+Update+Delete | View | View+Create+Update |
| Orders | View+Create+Update+Delete | View | — |
| Shipments | View+Create+Update+Delete | View | — |
| Payments | View+Create+Update+Delete | View | View+Create |
| Documents | View+Create+Delete | View | View+Create |
| Bank Accounts | View+Create+Update+Delete | View | — |
| Currencies | View+Create+Update+Delete | View | — |
| Settings (Company/Taxes/Audit) | View+Update | View | — |
| Dashboard | View | View | View |
| Users & Roles | View+Create+Update+Delete | — | — |

> **Menu visibility:** Every sidebar item is shown/hidden by its `view-*` permission. Roles without that permission do not see the menu at all.

---

## 10. Troubleshooting & Bug Fixes

### Bugs fixed in this release
| # | Bug | Fix |
|---|---|---|
| 1 | Supplier-type payment rows referenced a non-existent `suppliers.show` route → **RouteNotFoundException (500)** | Changed to `suppliers.edit` in `payments/partials/actions.blade.php` |
| 2 | Payment show page read `$doc->name`, which does not exist on the Document model → error/blank | Changed to `$doc->title` in `payments/show.blade.php` |
| 3 | Company settings always showed "Last saved: Never" | Now displays the real `updated_at` from the settings table |

### Common Issues & Solutions
| Problem | Solution |
|---|---|
| Sidebar appears empty | The user has no role → **Admin → Users → Edit → Role** |
| "You are not authorized" | The action's permission is missing → have an admin adjust role permissions |
| Delete button not visible | Status rule applies (delete only in draft/cancelled) |
| Edit button not visible | Edit is only allowed in draft/sent status |
| Prices do not change after selecting a currency | Refresh the page / ensure prices were entered in the base currency |
| PDF download fails | Invoice numbers containing `/` are renamed with `-` in the filename (already fixed) |
| "Last saved" empty | Nothing saved yet — save the settings once |

### After Deployment Checklist
1. Run `php artisan migrate`
2. Run `php artisan db:seed` (or specifically RolesAndPermissionsSeeder + InvoiceSystemSeeder)
3. Run `php artisan view:clear`
4. Log in with `admin@djagada.com / password` → first task: fill in Company settings
5. Verify currency rates on the Currencies page (check the Default badge)

---

*Documentation generated: August 2026. All screenshots were captured from the live application.*
