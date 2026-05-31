# Escape A Labyrinth

## Overview

This application solves the escape labyrinth problem using Double Breadth-First Search (BFS) algorithms. It finds the shortest path from the entry point (top-left corner) to the exit (bottom-right corner) of a labyrinth, with the ability to remove one wall as part of the solution strategy.

&nbsp;

## Project Structure
```
Apps/
  └ EscapeALabyrinth/
    ├── public/
    └────── index.php       # entry point
```

&nbsp;

## How It Works

The application:
1. Accepts a labyrinth represented as a matrix of 0s and 1s
2. Uses BFS to find the shortest path from entry to exit
3. Allows removal of one wall to optimize the path
4. Returns the length of the shortest path, counting all nodes traversed

Labyrinth Format:
- 0 represents passable space
- 1 represents impassable walls
- Entry point: top-left corner (0,0)
- Exit point: bottom-right corner (width-1, height-1)
- Movement: only cardinal directions (up, down, left, right) allowed
- No diagonal movement

Example:
- Input: [[0, 1, 1, 0], [0, 0, 0, 1], [1, 1, 0, 0], [1, 1, 1, 0]]
- Output: 7

## Requirements

- Labyrinth dimensions: 2 to 20 (width and height)
- Matrix format: 2D array with 0s and 1s

&nbsp;

## Running the Application

Navigate to the application's public directory and execute the command:

```
cd Apps/EscapeALabyrinth/public
php index.php solve-bfs-labirinth <labirinth> [options]
```

## Parameters

[matrix]: Optional - Pass the labyrinth as a 2D array after the command. Format example:
- [[0, 1, 1, 0], [0, 0, 0, 1], [1, 1, 0, 0], [1, 1, 1, 0]]

&nbsp;

## Options

**--get-labirinths-file**: Uses the public/labirinths.php file as the source for labyrinths
- This file should contain pre-defined labyrinth arrays
- Useful for batch processing multiple labyrinths

**--solve-all**: Process and solve all labyrinths
- If you provide only one labyrinth do not use this flag
- Without this flag, the application solves one labyrinth at a time
- With this flag, all available labyrinths are solved and results displayed

&nbsp;

## Examples

Solve a single labyrinth passed directly. Do not forget the ""s:
```
php index.php solve-bfs-labyrinth "[[0,1,1,0],[0,0,0,1],[1,1,0,0],[1,1,1,0]]"
```

Use labirinths from the public/labirinths.php file:
```
php index.php solve-bfs-labyrinth --get-labirinths-file
```

Solve all labyrinths from the file:
```
php index.php solve-bfs-labyrinth --get-labirinths-file --solve-all --print-warnings
```


&nbsp;

## Configuration


### Application Settings

Modify application-specific settings in core/config/app.php:
- Production mode
- Template paths
- Application-specific constants

### Commands

Add or modify available commands in core/config/commands.php:
- Command class mappings
- Command dependencies
- Command-specific configurations

### Validations

Control labyrinth validation rules in core/config/validations.php:
- Matrix format validation
- Dimension validation (2-20 range)
- Wall and passable space validation
- Entry and exit point validation

&nbsp;
&nbsp;

## Labyrinth File Format

The public/labirinths.php file should return an array of labyrinth matrices:

```php
<?php

return [
    [
        [0, 1, 1, 0],
        [0, 0, 0, 1],
        [1, 1, 0, 0],
        [1, 1, 1, 0]
    ],
    [
        [0, 0, 1],
        [1, 0, 1],
        [1, 0, 0]
    ],
    [
        [0, 1, 0, 0],
        [0, 1, 0, 1],
        [0, 0, 0, 0],
        [1, 1, 1, 0]
    ]
];
```

### Requirements:

- Each labyrinth must be a 2D array
- Dimensions must be between 2 and 20
- Only contains 0s (passable) and 1s (walls)
- Starting position (0,0) is always passable (0)
- Ending position (width-1, height-1) is always passable (0)

## Output

**Labyrinth**: The input matrix representation
Shortest Path Length: The total number of nodes to traverse from entry to exit
