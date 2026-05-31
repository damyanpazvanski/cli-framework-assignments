<?php

namespace CommonF\Apps;

use CommonF\Apps\CoreAppAbstract;
use CommonF\Interfaces\IGlobalHandler;
use CommonF\Interfaces\ICommand;
use CommonF\Interfaces\IValidator;
use CommonF\Interfaces\ILoggerAdapter;

class CoreCLIApp extends CoreAppAbstract
{
    protected array $commands = [];

    public function __construct(string $commandsConfigPath, string $appConfigPath, string $handlersConfigPath, string $validationsConfigPath) {
        parent::__construct($appConfigPath, $handlersConfigPath, $validationsConfigPath);
        $this->commands = $this->load($commandsConfigPath, 'commands');
    }

    public function resolveCommand(string $command, ...$args) {
        if (!isset($this->commands[$command])) {
            throw new \Exception('Command does not exist!');
        }

        $commandClass = array_key_first($this->commands[$command]);
        $builtCommandClass = array_shift($this->resolveNested($commandClass, $this->commands[$command], $args));

        if (is_string($builtCommandClass)) {
            $builtCommandClass = $this->resolve($builtCommandClass, [...$args], null, ICommand::class);
        }

        $validators = $this->resolveAllValidators($commandClass);

        $builtCommandClass->attachApp($this)->attachValidators($validators)->execute();
    }
}
