<?php

return [
    'name' => 'productCategory',
    'table' => 'product_categories',
    'label' => 'Category',
    'group' => 'Products',
    'icon' => 'fa-th-large',
    'permission_root' => 'categories',
    'soft_deletes' => true,
    'fields' => [
        ['name' => 'name', 'type' => 'string', 'label' => 'Name', 'required' => true, 'form' => 'text', 'rule' => 'max:150', 'unique' => true],
        ['name' => 'description', 'type' => 'text', 'label' => 'Description', 'nullable' => true, 'form' => 'textarea'],
    ],
];