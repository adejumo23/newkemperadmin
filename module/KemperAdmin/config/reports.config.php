<?php

use KemperAdmin\Helper\DateHelper;

return [
    'reports' => [
        'snapshot' => [
            'report-name' => "Snapshot Report",
            'description' => "Overall office production",
            'classification' => 'production',
            'permissions' => ['production'],
            'defaults' => [],
            'settings' => [
                'filters' => [
                    [
                        'type' => 'date-range',
                        'settings' => [],
                        'name' => 'date-range',
                        'description' => 'Select a date range to filter the report data by',
                        'defaults' => [
                            'start-date' => '01 ' . date('M') . ', ' . date('Y'),
                            'end-date' => [DateHelper::class, 'getLastDateofCurrentMonth'],
                        ]
                    ],
                ],
            ],
        ],
        'production' => [
            'report-name' => "Production Report",
            'description' => "YTD Per Agency and state",
            'classification' => 'production',
            'permissions' => ['production'],
            'settings' => [
                'filters' => [
                    [
                        'type' => 'date-range'
                    ],
                ],
            ],
            'defaults' => [],

        ],
        'conservation' => [
            'report-name' => "Conservation Report",
            'description' => "todo",
            'classification' => 'conservation',
            'permissions' => ['conservation'],
            'settings' => [
                'filters' => [
                    [
                        'type' => 'date-range'
                    ],
                ],
            ],
            'defaults' => [],

        ],
    ],
    'report_save_location' => "C:\\devroot\\reports",
];