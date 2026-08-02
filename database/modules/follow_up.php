<?php

return [
    'name' => 'followUp',
    'table' => 'follow_ups',
    'label' => 'Follow-up',
    'group' => 'CRM',
    'icon' => 'fa-calendar-check',
    'soft_deletes' => true,
    'fields' => [
        ['name' => 'followable_type', 'type' => 'string', 'label' => 'Entity Type', 'required' => true, 'form' => 'text', 'datatable' => false],
        ['name' => 'followable_id', 'type' => 'bigInteger', 'label' => 'Entity ID', 'required' => true, 'form' => 'number', 'datatable' => false],
        ['name' => 'type', 'type' => 'enum', 'label' => 'Type', 'required' => true, 'options' => ['call', 'email', 'meeting', 'task', 'note']],
        ['name' => 'due_date', 'type' => 'date', 'label' => 'Due Date', 'required' => true, 'form' => 'date'],
        ['name' => 'completed_at', 'type' => 'dateTime', 'label' => 'Completed', 'nullable' => true, 'form' => 'dateTime', 'datatable' => false],
        ['name' => 'note', 'type' => 'text', 'label' => 'Note', 'nullable' => true, 'form' => 'textarea'],
        ['name' => 'assigned_to', 'type' => 'foreignId', 'label' => 'Assigned To', 'nullable' => true, 'form' => 'relation', 'relation' => ['model' => 'user', 'table' => 'users', 'display' => 'name', 'constrain' => false]],
    ],
];
