<?php

use Apps\WordFrequencyCounter\Core\Commands\MigrateSQLite;
use Apps\WordFrequencyCounter\Core\Commands\RemoveSQLite;
use Apps\WordFrequencyCounter\Core\Repositories\WordsRepository;
use Apps\WordFrequencyCounter\Core\Loggers\SimpleLogger;

return [
    'migrate' => [
        MigrateSQLite::class => [
            WordsRepository::class,
            SimpleLogger::class,
        ]
    ],
    'remove' => [
        RemoveSQLite::class => [
            SimpleLogger::class,
        ]
    ],
];
