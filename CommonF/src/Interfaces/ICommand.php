<?php

namespace CommonF\Interfaces;

interface ICommand
{
    public function execute(): void;
    public function attachValidators(array $validators): self;
    public function getValidator(string $validatorClass): IValidator;
}
