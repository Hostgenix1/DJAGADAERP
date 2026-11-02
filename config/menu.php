<?php

return [
    0 => [
        'label' => 'CRM',
        'icon' => 'fa-folder-open',
        'items' => [
            0 => ['label' => 'Customer', 'route' => 'customers.index', 'icon' => 'fa-user', 'permission' => 'view-customers'],
            1 => ['label' => 'Contact', 'route' => 'contacts.index', 'icon' => 'fa-address-book', 'permission' => 'view-contacts'],
            2 => ['label' => 'Lead', 'route' => 'leads.index', 'icon' => 'fa-handshake', 'permission' => 'view-leads'],
            3 => ['label' => 'Follow-up', 'route' => 'follow_ups.index', 'icon' => 'fa-calendar-check', 'permission' => 'view-follow-ups'],
            4 => ['label' => 'Communication', 'route' => 'communications.index', 'icon' => 'fa-comments', 'permission' => 'view-communications'],
        ],
    ],
    1 => [
        'label' => 'Products',
        'icon' => 'fa-folder-open',
        'items' => [
            0 => ['label' => 'Brand', 'route' => 'brands.index', 'icon' => 'fa-tag', 'permission' => 'view-brands'],
            1 => ['label' => 'Category', 'route' => 'product_categories.index', 'icon' => 'fa-th-large', 'permission' => 'view-product_categories'],
            2 => ['label' => 'Supplier', 'route' => 'suppliers.index', 'icon' => 'fa-truck', 'permission' => 'view-suppliers'],
            3 => ['label' => 'Product', 'route' => 'products.index', 'icon' => 'fa-box', 'permission' => 'view-products'],
        ],
    ],
    2 => [
        'label' => 'Quotations',
        'icon' => 'fa-folder-open',
        'items' => [
            0 => ['label' => 'Quotation', 'route' => 'quotes.index', 'icon' => 'fa-file-invoice', 'permission' => 'view-quotes'],
            1 => ['label' => 'Proforma Invoice', 'route' => 'proforma_invoices.index', 'icon' => 'fa-file-alt', 'permission' => 'view-invoices'],
        ],
    ],
    3 => [
        'label' => 'Invoicing',
        'icon' => 'fa-folder-open',
        'items' => [
            0 => ['label' => 'Invoice', 'route' => 'invoices.index', 'icon' => 'fa-file-invoice-dollar', 'permission' => 'view-invoices'],
        ],
    ],
    4 => [
        'label' => 'Orders',
        'icon' => 'fa-folder-open',
        'items' => [
            0 => ['label' => 'Order', 'route' => 'orders.index', 'icon' => 'fa-shopping-cart', 'permission' => 'view-orders'],
        ],
    ],
    5 => [
        'label' => 'Shipments',
        'icon' => 'fa-ship',
        'items' => [
            0 => ['label' => 'Shipment', 'route' => 'shipments.index', 'icon' => 'fa-ship', 'permission' => 'view-shipments'],
        ],
    ],
    15 => [
        'label' => 'Inventory',
        'icon' => 'fa-warehouse',
        'items' => [
            0 => ['label' => 'Stock Levels', 'route' => 'stock.index', 'icon' => 'fa-boxes', 'permission' => 'view-stock'],
            1 => ['label' => 'Stock Movements', 'route' => 'stock.movements', 'icon' => 'fa-exchange-alt', 'permission' => 'view-stock'],
            2 => ['label' => 'Warehouses', 'route' => 'warehouses.index', 'icon' => 'fa-warehouse', 'permission' => 'view-warehouses'],
        ],
    ],
    6 => [
        'label' => 'Purchasing',
        'icon' => 'fa-folder-open',
        'items' => [
            0 => ['label' => 'Purchase Requests', 'route' => 'purchase_requests.index', 'icon' => 'fa-clipboard-check', 'permission' => 'view-purchase-requests'],
            1 => ['label' => 'Purchase Order', 'route' => 'purchase_orders.index', 'icon' => 'fa-clipboard-list', 'permission' => 'view-purchase-orders'],
            2 => ['label' => 'Supplier Bills', 'route' => 'supplier_bills.index', 'icon' => 'fa-file-invoice-dollar', 'permission' => 'view-supplier-bills'],
        ],
    ],
    7 => [
        'label' => 'Payments',
        'icon' => 'fa-folder-open',
        'items' => [
            0 => ['label' => 'Payment', 'route' => 'payments.index', 'icon' => 'fa-money-check-alt', 'permission' => 'view-payments'],
        ],
    ],
    8 => [
        'label' => 'Expenses',
        'icon' => 'fa-receipt',
        'items' => [
            0 => ['label' => 'Expenses', 'route' => 'expenses.index', 'icon' => 'fa-receipt', 'permission' => 'view-expenses'],
        ],
    ],
    9 => [
        'label' => 'Pricing',
        'icon' => 'fa-tags',
        'items' => [
            0 => ['label' => 'Supplier Prices', 'route' => 'supplier_prices.index', 'icon' => 'fa-truck-loading', 'permission' => 'view-supplier-prices'],
            1 => ['label' => 'Selling Prices', 'route' => 'selling_prices.index', 'icon' => 'fa-dollar-sign', 'permission' => 'view-selling-prices'],
        ],
    ],
    10 => [
        'label' => 'HR & Payroll',
        'icon' => 'fa-users-cog',
        'items' => [
            0 => ['label' => 'Employees', 'route' => 'employees.index', 'icon' => 'fa-user-tie', 'permission' => 'view-employees'],
            1 => ['label' => 'Attendance', 'route' => 'attendance.index', 'icon' => 'fa-calendar-check', 'permission' => 'view-attendance'],
            2 => ['label' => 'Leaves', 'route' => 'leaves.index', 'icon' => 'fa-umbrella-beach', 'permission' => 'view-leaves'],
            3 => ['label' => 'Payroll', 'route' => 'payroll.index', 'icon' => 'fa-money-check-alt', 'permission' => 'view-payroll'],
            4 => ['label' => 'My Payslips', 'route' => 'my-payslips.index', 'icon' => 'fa-file-pdf', 'permission' => 'view-own-payslips'],
        ],
    ],
    11 => [
        'label' => 'Documents',
        'icon' => 'fa-folder-open',
        'items' => [
            0 => ['label' => 'All Documents', 'route' => 'documents.index', 'icon' => 'fa-file-alt', 'permission' => 'view-documents'],
        ],
    ],
    16 => [
        'label' => 'Reports',
        'icon' => 'fa-chart-bar',
        'items' => [
            0 => ['label' => 'Reports & Analytics', 'route' => 'reports.index', 'icon' => 'fa-chart-bar', 'permission' => 'view-reports'],
        ],
    ],
    12 => [
        'label' => 'Settings',
        'icon' => 'fa-folder-open',
        'items' => [
            0 => ['label' => 'Bank Accounts', 'route' => 'bank-accounts.index', 'icon' => 'fa-university', 'permission' => 'view-bank-accounts'],
            1 => ['label' => 'Currency', 'route' => 'currencies.index', 'icon' => 'fa-money-bill', 'permission' => 'view-currencies'],
            2 => ['label' => 'Payment Terms', 'route' => 'admin.settings.payment_terms', 'icon' => 'fa-calendar-alt', 'permission' => 'view-settings'],
            3 => ['label' => 'Units', 'route' => 'admin.settings.units', 'icon' => 'fa-ruler-combined', 'permission' => 'view-settings'],
            4 => ['label' => 'Company', 'route' => 'admin.settings.company', 'icon' => 'fa-building', 'permission' => 'view-settings'],
            5 => ['label' => 'Taxes', 'route' => 'admin.settings.taxes', 'icon' => 'fa-percent', 'permission' => 'view-settings'],
            6 => ['label' => 'Audit Log', 'route' => 'admin.settings.audit', 'icon' => 'fa-history', 'permission' => 'view-settings'],
        ],
    ],
    13 => [
        'label' => 'User Management',
        'icon' => 'fa-folder-open',
        'items' => [
            0 => ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'fa-users', 'permission' => 'view-users'],
            1 => ['label' => 'Roles', 'route' => 'admin.roles.index', 'icon' => 'fa-user-shield', 'permission' => 'view-roles'],
        ],
    ],
];
