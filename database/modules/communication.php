<?php

return [
    'name' => 'communication',
    'table' => 'communications',
    'label' => 'Communication',
    'group' => 'CRM',
    'icon' => 'fa-comments',
    'soft_deletes' => false,
    'fields' => [
        ['name' => 'communicable_type', 'type' => 'string', 'label' => 'Entity Type', 'required' => true, 'form' => 'text', 'datatable' => false],
        ['name' => 'communicable_id', 'type' => 'bigInteger', 'label' => 'Entity ID', 'required' => true, 'form' => 'number', 'datatable' => false],
        ['name' => 'type', 'type' => 'enum', 'label' => 'Type', 'required' => true, 'options' => ['call', 'whatsapp', 'email', 'meeting', 'note']],
        ['name' => 'direction', 'type' => 'enum', 'label' => 'Direction', 'nullable' => true, 'options' => ['inbound', 'outbound']],
        ['name' => 'subject', 'type' => 'string', 'label' => 'Subject', 'nullable' => true, 'form' => 'text', 'rule' => 'max:255'],
        ['name' => 'body', 'type' => 'text', 'label' => 'Body', 'nullable' => true, 'form' => 'textarea'],
        ['name' => 'contact_id', 'type' => 'foreignId', 'label' => 'Contact', 'nullable' => true, 'form' => 'relation', 'relation' => ['model' => 'contact', 'table' => 'contacts', 'display' => 'full_name', 'constrain' => false]],
        ['name' => 'user_id', 'type' => 'foreignId', 'label' => 'User', 'nullable' => true, 'form' => 'relation', 'relation' => ['model' => 'user', 'table' => 'users', 'display' => 'name', 'constrain' => false]],
        ['name' => 'occurred_at', 'type' => 'dateTime', 'label' => 'Date/Time', 'required' => true, 'form' => 'dateTime'],
    ],
];
