<?php

namespace Common\Commands;

use Common\Interfaces\ICommand;
use Common\Interfaces\IValidator;

abstract class CommandAbstract implements ICommand
{
    protected array $validators = [];

    public function execute(): void {}

    public function attachValidators(array $validators): self {
        foreach ($validators as $instance) {
            $this->validators[get_class($instance)] = $instance;
        }

        return $this;
    }

    public function getValidator(string $validatorClass): IValidator {
        return $this->validators[$validatorClass] ?? null;
    }
}
