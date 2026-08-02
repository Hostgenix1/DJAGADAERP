<?php

return [
    'name' => 'brand',
    'table' => 'brands',
    'label' => 'Brand',
    'group' => 'Products',
    'icon' => 'fa-tag',
    'soft_deletes' => true,
    'fields' => [
        ['name' => 'name', 'type' => 'string', 'label' => 'Name', 'required' => true, 'form' => 'text', 'rule' => 'max:150', 'unique' => true],
        ['name' => 'slug', 'type' => 'string', 'label' => 'Slug', 'nullable' => true, 'form' => 'text', 'rule' => 'max:150'],
        ['name' => 'description', 'type' => 'text', 'label' => 'Description', 'nullable' => true, 'form' => 'textarea'],
    ],
];