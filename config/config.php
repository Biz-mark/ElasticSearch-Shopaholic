<?php

return [
    'hosts' => explode(',', env('ELASTICSEARCH_HOSTS')),

    'product' => [
        'index' => 'shopaholic_products',
        'key' => 'id',
    ],

    'category' => [
        'index' => 'shopaholic_categories',
        'key' => 'id',
    ],

    'brand' => [
        'index' => 'shopaholic_brands',
        'key' => 'id',
    ],

    'tag' => [
        'index' => 'shopaholic_tags',
        'key' => 'id',
    ]
];
