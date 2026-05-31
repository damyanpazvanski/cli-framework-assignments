<?php

namespace Apps\AdvertisingBidAuction\Core\Validators;

use CommonF\Validators\FileValidatorAbstract;

class CSVValidator extends FileValidatorAbstract
{
    public function validate(): bool {
        parent::validate();

        // Call Additional Custom Validations Here

        return true;
    }

    public function validateRowValues(array $row, int $line, int $rowId, string $rowBid, &$warnings = []): bool {
        if (
            filter_var($rowId, FILTER_VALIDATE_INT) == false ||
            filter_var($rowBid, FILTER_VALIDATE_FLOAT, [
                'options' => ['min_range' => 0, 'decimal' => $this->getOptions()['decimalDel']],
                'flags' => FILTER_FLAG_ALLOW_THOUSAND
            ]) == false
        ) {
            $warnings[] = 'Row - ' . ($line + 1) . ' has wrong data: ' . json_encode($row);
            return false;
        }

        return true;
    }

    public function validateFileRowsCount(int $index): bool {
        return $index > $this->configValidations['fileRows'];
    }

    public function getOptionsDecimalDigits(): int {
        return $this->getOptions()['decimalDigits'];
    }

    public function getInvalidFileMsg(): bool {
        return 'Invalid CSV File';
    }

    public function getValidateFileRowsCountMsg(): bool {
        return 'The lines size has exceeded: ' . $this->configValidations['fileRows'];
    }
}
