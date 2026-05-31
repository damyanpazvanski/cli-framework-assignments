<?php

use Apps\EscapeALabyrinth\Core\Commands\SolveBFSLabirinth;
use Apps\EscapeALabyrinth\Core\Validators\MatrixValidator;

return [
    SolveBFSLabirinth::class => [
        MatrixValidator::class => [
            'minMatrixCells' => 2,
            'maxMatrixCells' => 20,
        ]
    ]
];
