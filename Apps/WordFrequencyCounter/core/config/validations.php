<?php

use Apps\WordFrequencyCounter\Core\Validators\HTTPValidator;
use Apps\WordFrequencyCounter\Core\Controllers\WordFrequencyController;

return [
    WordFrequencyController::class => [
        HTTPValidator::class => [
            'wordsLengthBytesAllowed' => 500 * 1024 * 1024, // 500MB
            'chunkSize' => 4096, // 4KB
            'listsPageSize' => 10,
        ]
    ]
];
