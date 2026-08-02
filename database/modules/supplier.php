<?php

return [
    'name' => 'supplier',
    'table' => 'suppliers',
    'label' => 'Supplier',
    'group' => 'Products',
    'icon' => 'fa-truck',
    'menu_icon' => 'fa-folder-open',
    'soft_deletes' => true,
    'fields' => [
        ['name' => 'company_name', 'type' => 'string', 'label' => 'Company Name', 'required' => true, 'form' => 'text', 'rule' => 'max:255'],
        ['name' => 'contact_person', 'type' => 'string', 'label' => 'Contact Person', 'nullable' => true, 'form' => 'text', 'rule' => 'max:255'],
        ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'nullable' => true, 'form' => 'email', 'rule' => 'email|max:255'],
        ['name' => 'phone', 'type' => 'string', 'label' => 'Phone', 'nullable' => true, 'form' => 'text', 'rule' => 'max:50'],
        ['name' => 'address', 'type' => 'text', 'label' => 'Address', 'nullable' => true, 'form' => 'textarea'],
        ['name' => 'city', 'type' => 'string', 'label' => 'City', 'nullable' => true, 'form' => 'text', 'rule' => 'max:100'],
        ['name' => 'country', 'type' => 'string', 'label' => 'Country', 'nullable' => true, 'form' => 'text', 'rule' => 'max:100'],
        ['name' => 'payment_terms', 'type' => 'string', 'label' => 'Payment Terms', 'nullable' => true, 'form' => 'text', 'rule' => 'max:100'],
        ['name' => 'is_active', 'type' => 'boolean', 'label' => 'Active', 'default' => true, 'form' => 'boolean'],
    ],
];
