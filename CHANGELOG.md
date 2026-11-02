# Changelog

## v2.5.5 — 2026-11-02

### Changed
- Product create/edit form no longer shows the **Active** checkbox — new products start active automatically. Activation is now managed from the product list: every row's Actions column has an Enable/Disable toggle button (green/switch icon) that flips the status instantly without opening the form.

### Tests
- 66 automated tests / 214 assertions (new: form-created product defaults to active; list toggle flips enable ⇄ disable).

## v2.5.4 — 2026-11-02

### Added
- **Pack Types managed from Settings**: new Settings → Pack Types page (like Units) to add/edit/deactivate pack types — Carton, Box, Unit and Pallet come seeded. The Product form's Pack Type dropdown now reads this list, and custom values (e.g. Drum, Bundle) save without errors because `products.pack_type` was relaxed from a fixed enum to a string.
- **Dimensions as form parts**: the product form now has separate Length / Width / Height (cm) inputs instead of one free-text box; the printable Dimensions summary is generated automatically, e.g. `30 x 40 x 50 cm` (partial values supported: `120.5 cm`). Existing free-text dimension values are preserved.
- **SKU auto-generate + manual edit**: leave SKU blank and a sequential SKU (`SKU-00001`) is generated automatically on save. A "Generate" button next to the field suggests the next SKU and the field stays fully editable on both create and edit forms.
- New `GET products/sku/next` endpoint to fetch the next SKU suggestion.

### Fixed
- Creating a product without a Brand or Category no longer fails with a server error — `brand_id` and `category_id` are now optional in the database, matching the product form (SKU column was also relaxed to support auto-generation).
- The SKU input box now matches the height/size of the other fields (Name, Brand, Category…) while keeping the Generate button inside the box.
- Lead "Source" shows human-readable labels (Website, Referral, Cold Call, Marketing, Trade Show, Social Media, Other) in the form dropdown and in the leads list instead of raw values like `trade_show`.

### Changed
- Product form fields are back to the two-column grid (`col-md-6`) while the card still spans the full content width.

### Tests
- 64 automated tests / 207 assertions (new: SKU auto-generate/manual/duplicate/edit/suggestion, pack types settings + defaults + custom pack type on product, dimension summary generation, partial summary, update recompute, legacy dimensions untouched).

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
