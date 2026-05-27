<?php

namespace Common\Apps;

use Common\Interfaces\IGlobalHandler;
use Common\Interfaces\ICommand;
use Common\Interfaces\IValidator;

class CoreCLIApp
{
    protected array $appConfig = [];
    protected array $globalHandlers = [];
    protected array $commands = [];
    protected array $validations = [];
    protected IValidator $fileValidator;

    protected function __construct(string $appConfigPath, string $handlersConfigPath, string $commandsConfigPath, string $validationsConfigPath) {
        $this->appConfig = $this->load($appConfigPath, 'app');
        $this->globalHandlers = $this->load($handlersConfigPath, 'handlers');
        $this->commands = $this->load($commandsConfigPath, 'commands');
        $this->validations = $this->load($validationsConfigPath, 'validations');

        $this->resolveGlobalHandlers($this->globalHandlers);
    }

    protected function resolveGlobalHandlers(array $registerGlobalHandlers) {
        $inProduction = $this->appConfig['production'];

        $this->resolveAll($registerGlobalHandlers, function ($handler, $args) use ($inProduction) {
            $args['production'] = $inProduction;

            $handler::register($args);
        }, IGlobalHandler::class);
    }

    protected function resolveCommand(string $command, ...$args) {
        if (!isset($this->commands[$command])) {
            throw new \Exception('Command does not exist!');
        }

        $validators = $this->resolveAllValidators($this->commands[$command]);

        $this->resolve($this->commands[$command], $args, function ($handler, $args) use ($validators) {
            (new $handler(...$args))->attachValidators($validators)->execute();
        }, ICommand::class);
    }

    protected function resolveAllValidators(string $commandClass): array {
        $validators = [];

        $this->resolveAll($this->validations[$commandClass], function ($validator, $args) use (&$validators) {
            $validators[] = new $validator($args);
        }, IValidator::class);

        return $validators;
    }

    protected function resolveAll(array $classes, \Closure $callback, string $interface = null) {
        foreach ($classes as $handler => $args) {
            $this->resolve($handler ? $handler : $args, $handler ? $args : [], $callback, $interface);
        }
    }

    protected function resolve(string $className, $args, \Closure $callback = null, string $interface = null) {
        $reflection = new \ReflectionClass($className);
        
        if ($interface && !$reflection->implementsInterface($interface)) {
            throw new \Exception('The class: ' . $reflection . ' does not implement: ' . $interface);
        }

        return $callback ? $callback($className, $args ?? []) : new $className(...$args ?? []);
    }

    private function load(string $filePath, string $type)
    {
        $config = require $filePath;

        if (!is_array($config)) {
            throw new \TypeError('Cannot load the ' . $type . ' file!');
        }

        return $config;
    }
}
