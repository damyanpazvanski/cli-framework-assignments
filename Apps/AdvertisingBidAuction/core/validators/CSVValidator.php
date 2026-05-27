<?php

namespace Apps\AdvertisingBidAuction\Core\Validators;

use Common\Validators\FileValidatorAbstract;

class CSVValidator extends FileValidatorAbstract
{
    public function validate(): bool {
        parent::validate();

        // Call Additional Custom Validations Here

        return true;
    }
}
