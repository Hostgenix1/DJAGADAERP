<?php

/*
|--------------------------------------------------------------------------
| Sidebar menu
|--------------------------------------------------------------------------
|
| Groups are ordered to follow the business workflow:
| lead -> customer -> catalog -> pricing -> quotation -> order ->
| purchasing -> stock -> shipment -> invoice -> payment -> expenses.
| Administration (HR, settings, users) sits at the end.
|
*/

return [
    [
        'label' => 'CRM',
        'icon' => 'fa-address-book',
        'items' => [
            ['label' => 'Leads', 'route' => 'leads.index', 'icon' => 'fa-handshake', 'permission' => 'view-leads'],
            ['label' => 'Customers', 'route' => 'customers.index', 'icon' => 'fa-user', 'permission' => 'view-customers'],
            ['label' => 'Contacts', 'route' => 'contacts.index', 'icon' => 'fa-address-book', 'permission' => 'view-contacts'],
            ['label' => 'Follow-ups', 'route' => 'follow_ups.index', 'icon' => 'fa-calendar-check', 'permission' => 'view-follow-ups'],
            ['label' => 'Communications', 'route' => 'communications.index', 'icon' => 'fa-comments', 'permission' => 'view-communications'],
        ],
    ],
    [
        'label' => 'Products',
        'icon' => 'fa-boxes',
        'items' => [
            ['label' => 'Categories', 'route' => 'product_categories.index', 'icon' => 'fa-th-large', 'permission' => 'view-product_categories'],
            ['label' => 'Brands', 'route' => 'brands.index', 'icon' => 'fa-tag', 'permission' => 'view-brands'],
            ['label' => 'Suppliers', 'route' => 'suppliers.index', 'icon' => 'fa-truck', 'permission' => 'view-suppliers'],
            ['label' => 'Products', 'route' => 'products.index', 'icon' => 'fa-box', 'permission' => 'view-products'],
        ],
    ],
    [
        'label' => 'Pricing',
        'icon' => 'fa-tags',
        'items' => [
            ['label' => 'Supplier Prices', 'route' => 'supplier_prices.index', 'icon' => 'fa-truck-loading', 'permission' => 'view-supplier-prices'],
            ['label' => 'Selling Prices', 'route' => 'selling_prices.index', 'icon' => 'fa-dollar-sign', 'permission' => 'view-selling-prices'],
        ],
    ],
    [
        'label' => 'Quotations',
        'icon' => 'fa-file-signature',
        'items' => [
            ['label' => 'Quotations', 'route' => 'quotes.index', 'icon' => 'fa-file-invoice', 'permission' => 'view-quotes'],
            ['label' => 'Proforma Invoices', 'route' => 'proforma_invoices.index', 'icon' => 'fa-file-alt', 'permission' => 'view-invoices'],
        ],
    ],
    [
        'label' => 'Orders',
        'icon' => 'fa-shopping-cart',
        'items' => [
            ['label' => 'Orders', 'route' => 'orders.index', 'icon' => 'fa-shopping-cart', 'permission' => 'view-orders'],
        ],
    ],
    [
        'label' => 'Purchasing',
        'icon' => 'fa-shopping-basket',
        'items' => [
            ['label' => 'Purchase Requests', 'route' => 'purchase_requests.index', 'icon' => 'fa-clipboard-check', 'permission' => 'view-purchase-requests'],
            ['label' => 'Purchase Orders', 'route' => 'purchase_orders.index', 'icon' => 'fa-clipboard-list', 'permission' => 'view-purchase-orders'],
            ['label' => 'Supplier Bills', 'route' => 'supplier_bills.index', 'icon' => 'fa-file-invoice-dollar', 'permission' => 'view-supplier-bills'],
        ],
    ],
    [
        'label' => 'Inventory',
        'icon' => 'fa-warehouse',
        'items' => [
            ['label' => 'Warehouses', 'route' => 'warehouses.index', 'icon' => 'fa-warehouse', 'permission' => 'view-warehouses'],
            ['label' => 'Stock Levels', 'route' => 'stock.index', 'icon' => 'fa-boxes', 'permission' => 'view-stock'],
            ['label' => 'Stock Movements', 'route' => 'stock.movements', 'icon' => 'fa-exchange-alt', 'permission' => 'view-stock'],
        ],
    ],
    [
        'label' => 'Shipments',
        'icon' => 'fa-ship',
        'items' => [
            ['label' => 'Shipments', 'route' => 'shipments.index', 'icon' => 'fa-ship', 'permission' => 'view-shipments'],
        ],
    ],
    [
        'label' => 'Invoicing',
        'icon' => 'fa-file-invoice-dollar',
        'items' => [
            ['label' => 'Invoices', 'route' => 'invoices.index', 'icon' => 'fa-file-invoice-dollar', 'permission' => 'view-invoices'],
        ],
    ],
    [
        'label' => 'Payments',
        'icon' => 'fa-money-check-alt',
        'items' => [
            ['label' => 'Payments', 'route' => 'payments.index', 'icon' => 'fa-money-check-alt', 'permission' => 'view-payments'],
        ],
    ],
    [
        'label' => 'Expenses',
        'icon' => 'fa-receipt',
        'items' => [
            ['label' => 'Expenses', 'route' => 'expenses.index', 'icon' => 'fa-receipt', 'permission' => 'view-expenses'],
        ],
    ],
    [
        'label' => 'Documents',
        'icon' => 'fa-folder-open',
        'items' => [
            ['label' => 'All Documents', 'route' => 'documents.index', 'icon' => 'fa-file-alt', 'permission' => 'view-documents'],
        ],
    ],
    [
        'label' => 'Reports',
        'icon' => 'fa-chart-bar',
        'items' => [
            ['label' => 'Reports & Analytics', 'route' => 'reports.index', 'icon' => 'fa-chart-bar', 'permission' => 'view-reports'],
        ],
    ],
    [
        'label' => 'HR & Payroll',
        'icon' => 'fa-users-cog',
        'items' => [
            ['label' => 'Employees', 'route' => 'employees.index', 'icon' => 'fa-user-tie', 'permission' => 'view-employees'],
            ['label' => 'Attendance', 'route' => 'attendance.index', 'icon' => 'fa-calendar-check', 'permission' => 'view-attendance'],
            ['label' => 'Leaves', 'route' => 'leaves.index', 'icon' => 'fa-umbrella-beach', 'permission' => 'view-leaves'],
            ['label' => 'Payroll', 'route' => 'payroll.index', 'icon' => 'fa-money-check-alt', 'permission' => 'view-payroll'],
            ['label' => 'My Payslips', 'route' => 'my-payslips.index', 'icon' => 'fa-file-pdf', 'permission' => 'view-own-payslips'],
        ],
    ],
    [
        'label' => 'Settings',
        'icon' => 'fa-cog',
        'items' => [
            ['label' => 'Company', 'route' => 'admin.settings.company', 'icon' => 'fa-building', 'permission' => 'view-settings'],
            ['label' => 'Bank Accounts', 'route' => 'bank-accounts.index', 'icon' => 'fa-university', 'permission' => 'view-bank-accounts'],
            ['label' => 'Currencies', 'route' => 'currencies.index', 'icon' => 'fa-money-bill', 'permission' => 'view-currencies'],
            ['label' => 'Taxes', 'route' => 'admin.settings.taxes', 'icon' => 'fa-percent', 'permission' => 'view-settings'],
            ['label' => 'Payment Terms', 'route' => 'admin.settings.payment_terms', 'icon' => 'fa-calendar-alt', 'permission' => 'view-settings'],
            ['label' => 'Units', 'route' => 'admin.settings.units', 'icon' => 'fa-ruler-combined', 'permission' => 'view-settings'],
            ['label' => 'Pack Types', 'route' => 'admin.settings.pack_types', 'icon' => 'fa-cubes', 'permission' => 'view-settings'],
            ['label' => 'Audit Log', 'route' => 'admin.settings.audit', 'icon' => 'fa-history', 'permission' => 'view-settings'],
        ],
    ],
    [
        'label' => 'User Management',
        'icon' => 'fa-user-shield',
        'items' => [
            ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'fa-users', 'permission' => 'view-users'],
            ['label' => 'Roles', 'route' => 'admin.roles.index', 'icon' => 'fa-user-shield', 'permission' => 'view-roles'],
        ],
    ],
];
