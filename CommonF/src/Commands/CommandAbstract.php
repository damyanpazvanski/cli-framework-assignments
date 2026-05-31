<?php

namespace CommonF\Commands;

use CommonF\Apps\CoreAppAbstract;
use CommonF\Interfaces\ICommand;
use CommonF\Interfaces\IValidator;

abstract class CommandAbstract implements ICommand
{
    protected CoreAppAbstract $app;
    protected array $validators = [];

    public function execute(): void {}

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

    protected function prepareFlags(array $flags, array $possibleFlags): array {
        foreach ($possibleFlags as $possibleFlag => $value) {
            foreach ($flags as $flag) {
                if ($possibleFlag == $flag) {
                    $possibleFlags[$possibleFlag] = true;
                    break;
                }
            }
        }

        return $possibleFlags;
    }

    protected function printMsgsArr(array $msgs) {
        foreach ($msgs as $msg) {
            $this->printMsg($msg);
        }
    }

    protected function printMsg(string $msg) {
        echo $msg . PHP_EOL;
    }

    protected function canPrintWarnings(): bool {
        return $this->FLAGS['--print-warnings'];
    }

    protected function canContinue(): bool {
        return $this->FLAGS['--continue'];
    }
}
