# Changelog

## v2.5.3 — 2026-11-02

### Added
- Database indexes for CRM and documents (products: supplier/category/tax/part/brand, customers, contacts, leads, communications, follow-ups) — faster list screens and searches on the CRM and product catalogue.

### Changed
- Product create/edit forms are now truly full-width: fields are stacked top-to-bottom in standard `form-group` style and the card spans the entire content area (no leftover grid gutter on the right).
- Shipment screens and PDF use the label "ETD" for the `shipped_at` field (Estimated Time of Departure), aligned with shipping terminology.

## v2.5.2 — 2026-11-02

### Added
- **Purchase-order goods receipt**: marking a PO "Received" now puts the goods into stock automatically (one `in` movement per product line in the first active warehouse), and cancelling a received PO reverses its latest receipt. Every receipt cycle is traceable in the stock ledger (`PO:<number>:R<n>` / `C<n>` references).
- Cancel and "Reopen as Draft" buttons on the purchase-order page for the allowed status transitions.

### Fixed
- Purchase Order and Supplier Bill PDFs now use the company's default bank account (Bank Account module) with settings as fallback — bank details were empty unless the old hidden settings existed.
- "Create Supplier Bill" was possible for draft/cancelled purchase orders; now only confirmed or received POs can be billed.
- Order create/update is transactional and safely recalculates totals.
- "Mark Received" button no longer shows on an already-received purchase order.
- Purchase-order update recalculates from fresh items (stale eager-loaded items guard).

### Tests
- 52 automated tests / 180 assertions, incl. PO receive → stock-in → cancel reversal → re-receive cycle and bill-creation gates.

## v2.5.1 — 2026-11-02

### Changed
- Sidebar menu reordered to follow the business workflow: CRM → Products → Pricing → Quotations → Orders → Purchasing → Inventory → Shipments → Invoicing → Payments → Expenses → Documents/Reports → HR → Settings/Users.
- Sidebar icons are now colour-coded per module; submenu items show their own icons instead of identical circles, with a white hover/active state.

## v2.5.0 — 2026-11-02

### Added
- **Purchase Requests module** (`PR/YYYY/00001`) with items, approval workflow (submit → approve/reject) and conversion to a draft purchase order.
- **Quotation email** from the quote screen, with the PDF attached and an optional message.
- **Product image gallery**: upload multiple images, choose the primary image, and delete images.
- **Dashboard calendar** (FullCalendar) showing follow-ups, invoice due dates, shipment ETAs and purchase-request need-by dates.
- **Role presets** matching the agreed model: CEO, Finance, HR, Warehouse, Logistics and Viewer (in addition to Super Admin, Staff, Sales and Employee Self-Service).
- **Shipment Bill of Lading number and container numbers**, plus document uploads on shipments (e.g. scanned BOL).
- **SMTP runtime wiring**: settings saved in the admin panel now override `.env` mail configuration; the password is preserved when the field is left blank.
- `php artisan mail:test {email}` to verify outgoing mail.
- `php artisan db:snapshot` to create a MySQL dump (optionally gzipped) for delivery/backup.
- Settings are cached per request, reducing repeated queries on document and page rendering.
- README production checklist and delivery/backup instructions.

### Fixed
- Dashboard monthly revenue query is portable (works on SQLite for tests as well as MySQL).
- Purchase-request create form handles an empty request model correctly.
- Employee document uploads now use the same allow-list as shipments and other entities.
- Invoice create/update and stock movements are transactional with concurrency protection.

### Tests
- 46 automated tests covering auth, invoice/payment flow, documents (employee and shipment), payroll math, purchase-request lifecycle, quote email, product images, dashboard calendar, role presets and mail configuration.
