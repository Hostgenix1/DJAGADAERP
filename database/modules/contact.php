<?php

return [
    'name' => 'contact',
    'table' => 'contacts',
    'label' => 'Contact',
    'group' => 'CRM',
    'icon' => 'fa-address-book',
    'soft_deletes' => true,
    'fields' => [
        ['name' => 'customer_id', 'type' => 'foreignId', 'label' => 'Customer', 'form' => 'relation', 'relation' => ['model' => 'customer', 'table' => 'customers', 'display' => 'company_name', 'constrain' => false]],
        ['name' => 'full_name', 'type' => 'string', 'label' => 'Full Name', 'required' => true, 'form' => 'text', 'rule' => 'max:255'],
        ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'nullable' => true, 'form' => 'email', 'rule' => 'email|max:255'],
        ['name' => 'phone', 'type' => 'string', 'label' => 'Phone', 'nullable' => true, 'form' => 'text', 'rule' => 'max:50'],
        ['name' => 'position', 'type' => 'string', 'label' => 'Position', 'nullable' => true, 'form' => 'text', 'rule' => 'max:100'],
        ['name' => 'is_primary', 'type' => 'boolean', 'label' => 'Primary Contact', 'default' => false, 'form' => 'boolean'],
    ],
];