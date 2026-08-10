<?php

return [
    'units' => ['MT', 'KG', 'Carton', 'Bag', 'Piece', 'Can', 'Box', 'Pallet', 'Container', 'L', 'Unit', 'Other'],

    'incoterms' => ['EXW', 'FOB', 'CFR', 'CIF', 'DAP', 'DDP', 'FCA', 'CPT', 'CIP', 'DDU', 'Other'],

    'payment_terms' => [
        'Due on Receipt',
        'Net 15',
        'Net 30',
        'Net 45',
        'Net 60',
        '30% advance + 70% before shipment',
        'Balance due according to agreed terms',
    ],

    'default_payment_terms' => [
        'commercial' => 'Balance due according to agreed terms',
        'proforma' => '30% advance + 70% before shipment',
        'quote' => '30% advance + 70% before shipment',
        'purchase_order' => 'Supplier-specific terms',
        'supplier_bill' => 'Supplier-specific terms',
    ],

    'vat_modes' => [
        'none' => 'No VAT',
        'excluded' => 'VAT Excluded',
        'included' => 'VAT Included',
    ],
];
