<?php

return array (
  0 => 
  array (
    'label' => 'CRM',
    'icon' => 'fa-folder-open',
    'items' => 
    array (
      0 => array ('label' => 'Customer', 'route' => 'customers.index', 'icon' => 'fa-user', 'permission' => 'view-customers'),
      1 => array ('label' => 'Contact', 'route' => 'contacts.index', 'icon' => 'fa-address-book', 'permission' => 'view-contacts'),
      2 => array ('label' => 'Lead', 'route' => 'leads.index', 'icon' => 'fa-handshake', 'permission' => 'view-leads'),
      3 => array ('label' => 'Follow-up', 'route' => 'follow_ups.index', 'icon' => 'fa-calendar-check', 'permission' => 'view-follow-ups'),
      4 => array ('label' => 'Communication', 'route' => 'communications.index', 'icon' => 'fa-comments', 'permission' => 'view-communications'),
    ),
  ),
  1 => 
  array (
    'label' => 'Products',
    'icon' => 'fa-folder-open',
    'items' => 
    array (
      0 => array ('label' => 'Brand', 'route' => 'brands.index', 'icon' => 'fa-tag', 'permission' => 'view-brands'),
      1 => array ('label' => 'Category', 'route' => 'product_categories.index', 'icon' => 'fa-th-large', 'permission' => 'view-product_categories'),
      2 => array ('label' => 'Supplier', 'route' => 'suppliers.index', 'icon' => 'fa-truck', 'permission' => 'view-suppliers'),
      3 => array ('label' => 'Product', 'route' => 'products.index', 'icon' => 'fa-box', 'permission' => 'view-products'),
    ),
  ),
  2 => 
  array (
    'label' => 'Quotations',
    'icon' => 'fa-folder-open',
    'items' => 
    array (
      0 => array ('label' => 'Quotation', 'route' => 'quotes.index', 'icon' => 'fa-file-invoice', 'permission' => 'view-quotes'),
    ),
  ),
  3 => 
  array (
    'label' => 'Invoicing',
    'icon' => 'fa-folder-open',
    'items' => 
    array (
      0 => array ('label' => 'Invoice', 'route' => 'invoices.index', 'icon' => 'fa-file-invoice-dollar', 'permission' => 'view-invoices'),
    ),
  ),
  4 => 
  array (
    'label' => 'Orders',
    'icon' => 'fa-folder-open',
    'items' => 
    array (
      0 => array ('label' => 'Order', 'route' => 'orders.index', 'icon' => 'fa-shopping-cart', 'permission' => 'view-orders'),
    ),
  ),
  5 => 
  array (
    'label' => 'Shipments',
    'icon' => 'fa-folder-open',
    'items' => 
    array (
      0 => array ('label' => 'Shipment', 'route' => 'shipments.index', 'permission' => 'view-shipments'),
    ),
  ),
  6 => 
  array (
    'label' => 'Payments',
    'icon' => 'fa-folder-open',
    'items' => 
    array (
      0 => array ('label' => 'Payment', 'route' => 'payments.index', 'icon' => 'fa-money-check-alt', 'permission' => 'view-payments'),
    ),
  ),
  7 => 
  array (
    'label' => 'Documents',
    'icon' => 'fa-folder-open',
    'items' => 
    array (
      0 => array ('label' => 'All Documents', 'route' => 'documents.index', 'icon' => 'fa-file-alt', 'permission' => 'view-documents'),
    ),
  ),
  8 => 
  array (
    'label' => 'Settings',
    'icon' => 'fa-folder-open',
    'items' => 
    array (
      0 => array ('label' => 'Bank Accounts', 'route' => 'bank-accounts.index', 'icon' => 'fa-university', 'permission' => 'view-bank-accounts'),
      1 => array ('label' => 'Currency', 'route' => 'currencies.index', 'icon' => 'fa-money-bill', 'permission' => 'view-currencies'),
      2 => array ('label' => 'Company', 'route' => 'admin.settings.company', 'icon' => 'fa-building', 'permission' => 'view-settings'),
      3 => array ('label' => 'Taxes', 'route' => 'admin.settings.taxes', 'icon' => 'fa-percent', 'permission' => 'view-settings'),
      4 => array ('label' => 'Audit Log', 'route' => 'admin.settings.audit', 'icon' => 'fa-history', 'permission' => 'view-settings'),
    ),
  ),
  9 => 
  array (
    'label' => 'User Management',
    'icon' => 'fa-folder-open',
    'items' => 
    array (
      0 => array ('label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'fa-users', 'permission' => 'view-users'),
      1 => array ('label' => 'Roles', 'route' => 'admin.roles.index', 'icon' => 'fa-user-shield', 'permission' => 'view-roles'),
    ),
  ),
);
