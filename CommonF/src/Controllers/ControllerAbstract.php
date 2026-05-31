<?php

namespace CommonF\Controllers;

use CommonF\Apps\CoreAppAbstract;
use CommonF\Interfaces\IController;
use CommonF\Interfaces\IValidator;
use CommonF\Interfaces\IHTTPRequest;

abstract class ControllerAbstract implements IController
{
    protected CoreAppAbstract $app;
    protected array $validators = [];
    protected IHTTPRequest $request;

    public function __construct(IHTTPRequest $request) {
        $this->request = $request;
    }

    public function attachApp(CoreAppAbstract $app): self {
        $this->app = $app;
        return $this;
    }

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
