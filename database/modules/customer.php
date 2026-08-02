<?php

return [
    'name' => 'customer',
    'table' => 'customers',
    'label' => 'Customer',
    'group' => 'CRM',
    'icon' => 'fa-user',
    'soft_deletes' => true,
    'fields' => [
        ['name' => 'company_name', 'type' => 'string', 'label' => 'Company Name', 'required' => true, 'form' => 'text', 'rule' => 'max:255'],
        ['name' => 'contact_person', 'type' => 'string', 'label' => 'Contact Person', 'nullable' => true, 'form' => 'text', 'rule' => 'max:255'],
        ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'nullable' => true, 'form' => 'email', 'rule' => 'email|max:255', 'unique' => true],
        ['name' => 'phone', 'type' => 'string', 'label' => 'Phone', 'nullable' => true, 'form' => 'text', 'rule' => 'max:50'],
        ['name' => 'address', 'type' => 'text', 'label' => 'Address', 'nullable' => true, 'form' => 'textarea'],
        ['name' => 'city', 'type' => 'string', 'label' => 'City', 'nullable' => true, 'form' => 'text', 'rule' => 'max:100'],
        ['name' => 'country', 'type' => 'string', 'label' => 'Country', 'nullable' => true, 'form' => 'text', 'rule' => 'max:100'],
        ['name' => 'currency_id', 'type' => 'foreignId', 'label' => 'Currency', 'nullable' => true, 'form' => 'relation', 'relation' => ['model' => 'currency', 'table' => 'currencies', 'display' => 'code', 'constrain' => false]],
        ['name' => 'is_active', 'type' => 'boolean', 'label' => 'Active', 'default' => true, 'form' => 'boolean'],
    ],
];