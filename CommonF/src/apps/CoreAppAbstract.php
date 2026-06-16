<?php

namespace CommonF\Apps;

use CommonF\Interfaces\IGlobalHandler;
use CommonF\Interfaces\IController;
use CommonF\Interfaces\IValidator;
use CommonF\Interfaces\ILoggerAdapter;

abstract class CoreAppAbstract
{
    public array $appConfig = [];
    public array $globalHandlers = [];
    protected array $validations = [];
    protected IValidator $fileValidator;

    protected function __construct(string $appConfigPath, string $handlersConfigPath, string $validationsConfigPath) {
        $this->appConfig = $this->load($appConfigPath, 'app');
        $this->globalHandlers = $this->load($handlersConfigPath, 'handlers');
        $this->validations = $this->load($validationsConfigPath, 'validations');

        $this->resolveGlobalHandlers($this->globalHandlers);
    }

    protected function resolveGlobalHandlers(array $registerGlobalHandlers) {
        $inProduction = $this->appConfig['production'];

        $this->resolveAll($registerGlobalHandlers, function ($handler, $args) use ($inProduction) {
            $args['production'] = $inProduction;

            $handler::register($args);

            if ($args['logger']) {
                $handler::registerLogger($this->resolve($args['logger'], []));
            }
        }, IGlobalHandler::class);
    }

    protected function resolveNested(string $className, array $nestedArr, $args) {
        $dependencies = [];

        foreach ($nestedArr as $class => $deps) {
            if (!class_exists($class)) {
                if (is_string($deps) && class_exists($deps)) {
                    $dependencies[] = $this->resolve($deps, null);  // Array with multiple dependencies
                    continue;
                }

                $dependencies[] = $nestedArr;
                continue; // Bottom
            }

            $btmArgs = $this->resolveNested($class, is_array($deps) ? $deps : [$deps], $args);

            $dependencies[] = $this->resolve($class, is_array($btmArgs) ? [...$btmArgs, ...$args] : [$btmArgs, ...$args]);
        }

        return $dependencies;
    }

    protected function resolveAllValidators(string $className): array {
        $validators = [];
        if (!isset($this->validations[$className])) {
            return $validators;
        }

        $this->resolveAll($this->validations[$className], function ($validator, $args) use (&$validators) {
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

    protected function load(string $filePath, string $type)
    {
        $config = require $filePath;

        if (!is_array($config)) {
            throw new \TypeError('Cannot load the ' . $type . ' file!');
        }

        return $config;
    }
}
