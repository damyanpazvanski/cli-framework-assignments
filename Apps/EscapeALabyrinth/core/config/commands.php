<?php

use Apps\EscapeALabyrinth\Core\Commands\SolveBFSLabirinth;

use Apps\EscapeALabyrinth\Core\Loggers\SimpleLogger;

return [
    'solve-bfs-labirinth' => [
        SolveBFSLabirinth::class => [
            SimpleLogger::class,
        ]
    ]
];
