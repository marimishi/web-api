<?php
return [
    'controllers' => [
        'value' => [
            'namespaces' => [
                '\\Legacy\\API' => 'api', // namespace => папка
            ]
        ],
        'readonly' => true,
    ],
    'services' => [
        'value' => [
            'Legacy.API.IblockTemplate' => [
                'className' => '\\Legacy\\API\\IblockTemplate',
            ],
        ],
        'readonly' => true,
    ],
];