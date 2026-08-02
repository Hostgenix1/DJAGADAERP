<?php

return [
    'name' => 'lead',
    'table' => 'leads',
    'label' => 'Lead',
    'group' => 'CRM',
    'icon' => 'fa-handshake',
    'soft_deletes' => true,
    'fields' => [
        ['name' => 'company_name', 'type' => 'string', 'label' => 'Company Name', 'required' => true, 'form' => 'text', 'rule' => 'max:255'],
        ['name' => 'contact_name', 'type' => 'string', 'label' => 'Contact Name', 'nullable' => true, 'form' => 'text', 'rule' => 'max:255'],
        ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'nullable' => true, 'form' => 'email', 'rule' => 'email|max:255'],
        ['name' => 'phone', 'type' => 'string', 'label' => 'Phone', 'nullable' => true, 'form' => 'text', 'rule' => 'max:50'],
        ['name' => 'source', 'type' => 'enum', 'label' => 'Source', 'nullable' => true, 'options' => ['website', 'referral', 'cold_call', 'marketing', 'trade_show', 'social_media', 'other']],
        ['name' => 'status', 'type' => 'enum', 'label' => 'Status', 'required' => true, 'options' => ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'], 'default' => 'new'],
        ['name' => 'expected_amount', 'type' => 'decimal', 'label' => 'Expected Amount', 'nullable' => true, 'precision' => 15, 'scale' => 2, 'form' => 'number', 'rule' => 'numeric'],
        ['name' => 'currency_id', 'type' => 'foreignId', 'label' => 'Currency', 'nullable' => true, 'form' => 'relation', 'relation' => ['model' => 'currency', 'table' => 'currencies', 'display' => 'code', 'constrain' => false]],
        ['name' => 'expected_date', 'type' => 'date', 'label' => 'Expected Close Date', 'nullable' => true, 'form' => 'date'],
        ['name' => 'owner_id', 'type' => 'foreignId', 'label' => 'Owner', 'nullable' => true, 'form' => 'relation', 'relation' => ['model' => 'user', 'table' => 'users', 'display' => 'name', 'constrain' => false]],
        ['name' => 'customer_id', 'type' => 'foreignId', 'label' => 'Converted Customer', 'nullable' => true, 'form' => 'relation', 'relation' => ['model' => 'customer', 'table' => 'customers', 'display' => 'company_name', 'constrain' => false]],
        ['name' => 'note', 'type' => 'text', 'label' => 'Notes', 'nullable' => true, 'form' => 'textarea'],
    ],
];
