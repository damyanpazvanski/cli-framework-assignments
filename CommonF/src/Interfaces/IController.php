<?php

namespace CommonF\Interfaces;

interface IController
{
    public function attachValidators(array $validators): self;
    public function getValidator(string $validatorClass): IValidator;
}
