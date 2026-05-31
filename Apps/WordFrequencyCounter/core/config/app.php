<?php

return [
    'production' => true,                                   // Exclude too comprehensive errors
    'selectedDatabase' => 'sqlite',
    'database' => [
        'sqlite' => [
            'file' => 'shared_management_data.db',
            'host' => __DIR__ . '/../../../../db_files',    // Its mentioned in the .gitignore
        ],
    ],
    'templatesPath' => __DIR__ . '/../templates/',
];
