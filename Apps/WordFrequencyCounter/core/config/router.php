<?php

use Apps\WordFrequencyCounter\Core\Controllers\WordFrequencyController;
use Apps\WordFrequencyCounter\Core\Controllers\HomeController;

use Apps\WordFrequencyCounter\Core\Requests\Request;
use Apps\WordFrequencyCounter\Core\Repositories\WordsRepository;

return [
    '/home' => [
        'options' => [
            'name' => 'home',
            'action' => 'home',
            'method' => 'GET'
        ],
        'controller' => [
            HomeController::class => [
                Request::class,
            ]
        ]
    ],
    '/words-frequency-counter-list' => [
        'options' => [
            'name' => 'wordsFrequencyCounterList',
            'action' => 'wordsLists',
            'method' => 'GET'
        ],
        'controller' => [
            WordFrequencyController::class => [
                Request::class,
                WordsRepository::class,
            ]
        ]
    ],
    '/word-frequency/word' => [
        'options' => [
            'name' => 'wordsFrequencyCounterViewWord',
            'action' => 'word',
            'method' => 'GET'
        ],
        'controller' => [
            WordFrequencyController::class => [
                Request::class,
                WordsRepository::class,
            ]
        ]
    ],
    '/words-frequency-counter' => [
        'options' => [
            'name' => 'wordsFrequencyCounter',
            'action' => 'updateWordsList',
            'method' => 'GET'
        ],
        'controller' => [
            WordFrequencyController::class => [
                Request::class,
                WordsRepository::class,
            ]
        ]
    ],
    '/words-frequency-counter-process' => [
        'options' => [
            'name' => 'wordsFrequencyCounterProcess',
            'action' => 'processUpdateWordsList',
            'method' => 'POST'
        ],
        'controller' => [
            WordFrequencyController::class => [
                Request::class,
                WordsRepository::class,
            ]
        ]
    ],
    'otherwise' => [
        'options' => [
            'action' => 'notFound404',
            'method' => 'GET'
        ],
        'controller' => [
            HomeController::class => [
                Request::class,
            ]
        ]
    ]
];
