<?php

return [
    'name' => 'product',
    'table' => 'products',
    'label' => 'Product',
    'group' => 'Products',
    'icon' => 'fa-box',
    'soft_deletes' => true,
    'fields' => [
        ['name' => 'sku', 'type' => 'string', 'label' => 'SKU', 'required' => true, 'form' => 'text', 'rule' => 'max:50', 'unique' => true],
        ['name' => 'name', 'type' => 'string', 'label' => 'Name', 'required' => true, 'form' => 'text', 'rule' => 'max:255'],
        ['name' => 'brand_id', 'type' => 'foreignId', 'label' => 'Brand', 'form' => 'relation', 'relation' => ['model' => 'brand', 'table' => 'brands', 'display' => 'name', 'constrain' => false]],
        ['name' => 'category_id', 'type' => 'foreignId', 'label' => 'Category', 'form' => 'relation', 'relation' => ['model' => 'productCategory', 'table' => 'product_categories', 'display' => 'name', 'constrain' => false]],
        ['name' => 'supplier_id', 'type' => 'foreignId', 'label' => 'Supplier', 'nullable' => true, 'form' => 'relation', 'relation' => ['model' => 'supplier', 'table' => 'suppliers', 'display' => 'company_name', 'constrain' => false]],
        ['name' => 'buy_price', 'type' => 'decimal', 'label' => 'Buy Price', 'nullable' => true, 'precision' => 15, 'scale' => 2, 'form' => 'number', 'rule' => 'numeric'],
        ['name' => 'sell_price', 'type' => 'decimal', 'label' => 'Sell Price', 'nullable' => true, 'precision' => 15, 'scale' => 2, 'form' => 'number', 'rule' => 'numeric'],
        ['name' => 'currency_id', 'type' => 'foreignId', 'label' => 'Currency', 'nullable' => true, 'form' => 'relation', 'relation' => ['model' => 'currency', 'table' => 'currencies', 'display' => 'code', 'constrain' => false]],
        ['name' => 'tax_id', 'type' => 'foreignId', 'label' => 'Tax', 'nullable' => true, 'form' => 'relation', 'relation' => ['model' => 'tax', 'table' => 'taxes', 'display' => 'name', 'constrain' => false]],
        ['name' => 'unit', 'type' => 'string', 'label' => 'Unit', 'nullable' => true, 'form' => 'text', 'rule' => 'max:20'],
        ['name' => 'pack_qty', 'type' => 'integer', 'label' => 'Pack Qty', 'nullable' => true, 'form' => 'number'],
        ['name' => 'pack_type', 'type' => 'enum', 'label' => 'Pack Type', 'nullable' => true, 'options' => ['carton', 'box', 'unit', 'pallet']],
        ['name' => 'weight_kg', 'type' => 'decimal', 'label' => 'Weight (kg)', 'nullable' => true, 'precision' => 10, 'scale' => 3, 'form' => 'number'],
        ['name' => 'dimensions', 'type' => 'string', 'label' => 'Dimensions', 'nullable' => true, 'form' => 'text', 'rule' => 'max:100'],
        ['name' => 'specifications', 'type' => 'longText', 'label' => 'Specifications', 'nullable' => true, 'form' => 'textarea'],
        ['name' => 'certificates', 'type' => 'longText', 'label' => 'Certificates', 'nullable' => true, 'form' => 'textarea'],
        ['name' => 'is_active', 'type' => 'boolean', 'label' => 'Active', 'default' => true, 'form' => 'boolean'],
    ],
];
