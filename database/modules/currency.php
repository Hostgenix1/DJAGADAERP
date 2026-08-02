<?php

return [
    'name' => 'currency',
    'table' => 'currencies',
    'label' => 'Currency',
    'group' => 'Settings',
    'icon' => 'fa-money-bill',
    'soft_deletes' => true,
    'fields' => [
        ['name' => 'code', 'type' => 'string', 'label' => 'Code', 'length' => 8, 'required' => true, 'form' => 'text', 'rule' => 'max:8', 'unique' => true],
        ['name' => 'name', 'type' => 'string', 'label' => 'Name', 'required' => true, 'form' => 'text', 'rule' => 'max:100'],
        ['name' => 'symbol', 'type' => 'string', 'label' => 'Symbol', 'nullable' => true, 'length' => 8, 'form' => 'text', 'rule' => 'max:8'],
        ['name' => 'rate', 'type' => 'decimal', 'label' => 'Rate (vs base)', 'nullable' => true, 'precision' => 15, 'scale' => 4, 'form' => 'number', 'rule' => 'numeric'],
        ['name' => 'is_active', 'type' => 'boolean', 'label' => 'Active', 'default' => true, 'form' => 'boolean'],
    ],
];