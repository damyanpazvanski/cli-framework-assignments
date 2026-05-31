<?php

namespace Apps\EscapeALabyrinth\Core\Validators;

use CommonF\Validators\ValidatorAbstract;

class MatrixValidator extends ValidatorAbstract
{
    protected $matrix;
    protected array $matrixPossibleValues;

    public function validate(): bool {
        return
            !empty($this->matrix) && !empty($this->matrixPossibleValues) &&
            $this->validateJSONObj() &&
            $this->validateMatrixInDeep() &&
            $this->validateMatrixSize();
    }

    public function validateJSONObj(): bool {
        return json_last_error() == JSON_ERROR_NONE || is_array($this->matrix);
    }

    public function validateMatrixInDeep(): bool {
        foreach ($this->matrix as $row) {
            // Validate Matrix as Cube w=h
            if (!is_array($row) || count($this->matrix) !== count($row)) {
                throw new \Exception($this->getBadMatrixMsg());
                break;
            }

            // Check the nested elements for possible values
            foreach ($row as $value) {
                if (!in_array($value, $this->matrixPossibleValues, true)) {
                    throw new \Exception('Wrong Path Values');
                    break 2;
                }
            }
        }

        return true;
    }

    public function validateMatrixSize(): bool {
        if (
            count($this->matrix) >= $this->configValidations['minMatrixCells'] &&
            count($this->matrix) <= $this->configValidations['maxMatrixCells'] &&
            count($this->matrix[0]) >= $this->configValidations['maxMatrixCells'] &&
            count($this->matrix[0]) <= $this->configValidations['maxMatrixCells']
        ) {
            throw new \Exception('Outside the possible Matrix Dimensions');
        }

        return true;
    }

    public function setMatrixObj($matrix) {
        $this->matrix = $matrix;
    }

    public function setValidNestedValues(array $possibleValues) {
        $this->matrixPossibleValues = $possibleValues;
    }

    public function getBadMatrixMsg() {
        return "Error: Bad Matrix Formatting";
    }
}
