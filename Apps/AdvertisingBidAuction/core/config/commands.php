<?php

use Apps\AdvertisingBidAuction\Core\Commands\BestAdSecondBid;

use Apps\AdvertisingBidAuction\Core\Repositories\CSVFileRepository;
use Apps\AdvertisingBidAuction\Core\Files\CSVFile;
use Apps\AdvertisingBidAuction\Core\Loggers\SimpleLogger;

return [
    'best-ad-second-bid' => [
        BestAdSecondBid::class => [
            CSVFileRepository::class => [
                CSVFile::class,
            ],
            SimpleLogger::class,
        ]
    ]
];
