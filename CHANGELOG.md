# Changelog

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
