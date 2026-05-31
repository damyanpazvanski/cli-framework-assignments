<?php

use Apps\AdvertisingBidAuction\Core\Validators\CSVValidator;
use Apps\AdvertisingBidAuction\Core\Commands\BestAdSecondBid;

return [
    BestAdSecondBid::class => [
        CSVValidator::class => [
            'ext' => 'csv',
            'fileRows' => 10000,
            'maxByteSize' => 100000,
            'headers' => ['ad_id', 'bid'],
            'options' => [
                'decimalDigits' => 4,
                'decimalDel' => '.'
            ]
        ]
    ]
];
