<?php

namespace CommonF\Apps;

use CommonF\Apps\CoreAppAbstract;
use CommonF\Interfaces\IGlobalHandler;
use CommonF\Interfaces\IController;
use CommonF\Interfaces\IValidator;
use CommonF\Interfaces\ILoggerAdapter;

class CoreHTTPApp extends CoreAppAbstract
{
    protected array $routes = [];

    protected function __construct(string $routerConfigPath, string $appConfigPath, string $handlersConfigPath, string $validationsConfigPath) {
        parent::__construct($appConfigPath, $handlersConfigPath, $validationsConfigPath);
        $this->routes = $this->load($routerConfigPath, 'router');
    }

    public function urlByRouteName($name) {
        foreach ($this->routes as $url => $route) {
            if ($route['options']['name'] === $name) {
                return $url;
            }
        }

        return 'otherwise';
    }

    protected function resolveController(string $route, ...$args) {
        if (!isset($this->routes[$route])) {
            throw new \Exception('Route does not exist!');
        }

        $controller = array_key_first($this->routes[$route]['controller']);
        $builtControllerClass = array_shift($this->resolveNested($controller, $this->routes[$route]['controller'], $args));

        if (is_string($builtControllerClass)) {
            $builtControllerClass = $this->resolve($builtControllerClass, [...$args], null, IController::class);
        }

        $validators = $this->resolveAllValidators($controller);

        $action = $this->routes[$route]['options']['action'];
        $response = $builtControllerClass->attachApp($this)->attachValidators($validators)->$action();

        $params = [];
        if (isset($response[1]) && is_array($response[1])) {
            $params = $response[1];
        }

        if (!isset($response[0])) {
            return;
        }

        $this->render($response[0], $this->getTemplateRoot(), $params);
    }

    protected function render(string $path, string $root, $params = [])
    {
        $file_path = $root . $path . '.php';
        if (!file_exists($file_path)) {
            throw new \Exception('This template doesn\'t exist!');
        }

        $params = $params;
        
        include_once $file_path;
    }

    protected function findRouteKey($path, $method = 'GET') {
        $path = preg_split('/[\/]/', $path);
        $uri = $path[1];

        if (isset($path[2])) {
            $uri .= '/' . $path[2];
        }

        $uri = explode('?', $uri);
        $prefix = substr($uri[0], 0, 1);

        if ($prefix !== '/') {
            $uri[0] = '/' . $uri[0];
        }

        foreach ($this->routes as $key => $route) {
            if ($key !== 'otherwise' && $uri[0] == $key && strtolower($method) == strtolower($route['options']['method'])) {
                return $key;
            }
        }

        if (isset($this->routes['otherwise'])) {
            return 'otherwise';
        }

        throw new \Exception('Route does not exist');
    }

    protected function getTemplateRoot() {
        return $this->appConfig['templatesPath'];
    }
}
