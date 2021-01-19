<?php

return [
    'app' => [
        'routes' => [
            'cms' => env('ROUTE_CMS'),
            'api' => env('ROUTE_API'),
        ],
        'domains' => [
            'cms' => env('DOMAIN_CMS'),
            'api' => env('DOMAIN_API'),
        ]
    ]
];
