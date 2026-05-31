<?php

namespace CommonF\Validators;

use CommonF\Interfaces\IValidator;

abstract class ValidatorAbstract implements IValidator
{
    protected array $configValidations;

    public function __construct(array $configValidations) {
        $this->configValidations = $configValidations;
	}

    public function validate(): bool {
        return true;
    }
}
