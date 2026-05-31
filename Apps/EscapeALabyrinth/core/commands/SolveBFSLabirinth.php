<?php

namespace Apps\EscapeALabyrinth\Core\Commands;

use Apps\EscapeALabyrinth\Core\Validators\MatrixValidator;
use CommonF\Commands\CommandAbstract;
use Apps\EscapeALabyrinth\Core\Loggers\SimpleLogger;
use Apps\EscapeALabyrinth\Core\Structures\FIFOQueue;

class SolveBFSLabirinth extends CommandAbstract
{
    /**
     * 0 - Free path
     * 1 - Wall
     */
    protected const POSSIBLE_VALUES_MAP = [0, 1];
    
    /**
     * Possible passed walls
     */
    protected const POSSIBLE_PASSED_WALLS = 1;

    /**
     * Matrix Directions in 2D Dimention
     * [h, w]
     */
    protected const MATRIX_DIRECTIONS = [
        [-1, 0], // Up
        [1, 0],  // Down
        [0, -1], // Left
        [0, 1]   // Right
    ];

    /**
     * Define Flags
     */
    protected array $FLAGS = [
        '--get-labirinths-file' => false,
        '--solve-all' => false,
    ];

    protected SimpleLogger $simpleLogger;
    protected array $options;
    protected MatrixValidator $matrixValidator;

    public function __construct(SimpleLogger $simpleLogger, $options, $flags) {
        $this->simpleLogger = $simpleLogger;
        $this->options = $options;
        $this->FLAGS = $this->prepareFlags($flags, $this->FLAGS);
    }

    public function execute(): void {
        $labirinths = [];
        $this->matrixValidator = $this->getValidator(MatrixValidator::class);
        $this->matrixValidator->setValidNestedValues(self::POSSIBLE_VALUES_MAP);

        // Use Default Labirinths File
        if ($this->canGetLabirinthsFile()) {
            if (file_exists($this->app->appConfig['default_labirinth_file_path'])) {
                $labirinths = include_once $this->app->appConfig['default_labirinth_file_path'];
            } else {
                throw new \Exception('Labirinth File Does Not Exist');
            }
        } else {
            $labirinths = json_decode($this->options[0], true);
            if (!$this->canSolveAll()) {
                $labirinths = [$labirinths];
            }
        }

        if (!$this->canSolveAll() && count($labirinths) > 1) {
            throw new \Exception('Too many labirinths');
        }

        // Could be more than one
        foreach ($labirinths as $matrix) {
            $this->matrixValidator->setMatrixObj($matrix);
            if (!$this->matrixValidator->validate()) {
                throw new \Exception($this->matrixValidator->getBadMatrixMsg());
            }
        }

        // Use Validated Labirinth
        $this->solve($labirinths);
    }

    public function solve(array $labirinths) {
        foreach ($labirinths as $matrix) {
            $fastestSteps = $this->solution($matrix);

            if ($fastestSteps) {
                $this->simpleLogger->success('Output: ' . $fastestSteps);
            } else {
                $this->simpleLogger->error('No possible paths');
            }
        }
    }

    /**
     * Wall Scan Loop: Double-BFS solution
     * For every wall found, it checks its 4 neighbors
     * 
     * @param array $matrix - Labirinth
     * @return int - min path or 0 for no path
     */
    protected function solution($matrix) {
        $h = count($matrix);
        $w = count($matrix[0]); // Using index 0 to correctly get width dynamically
        $maxSteps = $h * $w;
        
        // Find possible paths from the top-left - start (0, 0)
        $fromStart = $this->findBFSPath(0, 0, $matrix, $h, $w);
        
        // Find possible paths from the bottom-right - exit (h-1, w-1)
        $fromExit = $this->findBFSPath($h - 1, $w - 1, $matrix, $h, $w);

        // Path length if we don't remove a wall
        $minPath = ($fromStart[$h - 1][$w - 1] > 0) ? $fromStart[$h - 1][$w - 1] : $maxSteps;

        // Scan every single cell in the grid
        for ($x = 0; $x < $h; $x++) {
            for ($y = 0; $y < $w; $y++) {
                
                // Checks The walls (1) For Shortcut Opportunity
                if ($matrix[$x][$y] == 1) {
                    $bestFromStart = $maxSteps;
                    $bestFromExit = $maxSteps;

                    // Iterate each direction
                    foreach (self::MATRIX_DIRECTIONS as [$dx, $dy]) {
                        $nx = $x + $dx;
                        $ny = $y + $dy;

                        // Stay within the grid
                        if ($nx >= 0 && $nx < $h && $ny >= 0 && $ny < $w) {

                            // Check if a neighbor can connect back to the start
                            if ($fromStart[$nx][$ny] > 0) {
                                $bestFromStart = min($bestFromStart, $fromStart[$nx][$ny]);
                            }

                            // Check if a neighbor can connect forward to the exit
                            if ($fromExit[$nx][$ny] > 0) {
                                $bestFromExit = min($bestFromExit, $fromExit[$nx][$ny]);
                            }
                        }
                    }
                    
                    // Mark every wall that bridges a start path and an exit path
                    if ($bestFromStart !== $maxSteps && $bestFromExit !== $maxSteps) {
                        $shortcutLength = $bestFromStart + $bestFromExit + 1;
                        $minPath = min($minPath, $shortcutLength);
                    }
                }
            }
        }
        
        return $minPath == $maxSteps ? 0 : $minPath;
    }

    /**
     * BFS - Gets possible distances from the start and end places
     */
    protected function findBFSPath($startX, $startY, $labirinth, $h, $w) {
        // Fill a grid with 0s to represent unvisited cells
        $distances = array_fill(0, $h, array_fill(0, $w, 0));

        $queue = new FIFOQueue();
        $queue->enqueue([$startX, $startY]);    // Add first position
        $distances[$startX][$startY] = 1;       // Count the starting cell as step 1

        while (!$queue->isEmpty()) {
            list($x, $y) = $queue->dequeue();

            // Iterate each direction
            foreach (self::MATRIX_DIRECTIONS as [$dx, $dy]) {
                $nx = $x + $dx;
                $ny = $y + $dy;

                // Stay within the grid
                if ($nx >= 0 && $nx < $h && $ny >= 0 && $ny < $w) {

                    // Only step on available paths (0) that we haven't visited yet
                    if ($labirinth[$nx][$ny] == 0 && $distances[$nx][$ny] == 0) {
                        $distances[$nx][$ny] = $distances[$x][$y] + 1;
                        $queue->enqueue([$nx, $ny]);
                    }
                }
            }
        }

        return $distances;
    }

    protected function canGetLabirinthsFile(): bool {
        return $this->FLAGS['--get-labirinths-file'];
    }

    protected function canSolveAll(): bool {
        return $this->FLAGS['--solve-all'];
    }
}
