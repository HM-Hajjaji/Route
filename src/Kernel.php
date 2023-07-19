<?php

namespace Route;

use ReflectionClass;
use ReflectionException;

abstract class Kernel
{
    protected string $controller_dir;
    protected static array $routes = [];
    protected function handleControllers(string $path):array
    {
        $controllers = [];
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $pathFile = sprintf("%s/%s", $path, $file);
            if (is_file($pathFile) && pathinfo($file, PATHINFO_EXTENSION) === "php") {
                $content = file_get_contents($pathFile);
                preg_match('/namespace\s+(.*?);/', $content, $namespaceMatches);
                preg_match('/class\s+(\w+)/', $content, $classNameMatches);
                if (isset($namespaceMatches[1]) && isset($classNameMatches[1])) {
                    $controllers[] = $namespaceMatches[1] . "\\" . $classNameMatches[1];
                }
            } elseif (is_dir($pathFile)) {
                foreach ($this->handleControllers($pathFile) as $class) {
                    $controllers[] = $class;
                }
            }
        }

        return $controllers;
    }

    /**
     * @throws ReflectionException
     * @throws \Exception
     */
    protected function handleReflections():array
    {
        $reflections = [];
        foreach ($this->handleControllers($this->controller_dir) as $class)
        {
            try {
                $reflections[] = new ReflectionClass($class);
            } catch (ReflectionException $e) {
                throw new \Exception($e->getMessage());
            }
        }
        return $reflections;
    }

    protected function handleListRoute(string $path, string $url, array|callable $action, string|array $method):void
    {
        $path = trim($path);
        $url = trim($url);
        $url = !str_ends_with($url,"/") ? $url."/":$url;
        if (is_array($method))
        {
            if (isset($method[0]))
            {
                self::$routes[$method[0]][$path] = ['url' => trim($url),"action" => $action];
            }
            if (isset($method[1]))
            {
                self::$routes[$method[1]][$path] = ['url' => trim($url),"action" => $action];
            }
            return;
        }
        self::$routes[$method][$path] = ['url' => trim($url),"action" => $action];
    }

    /**
     * @throws \Exception
     */
    protected function execute(string $method, string $url):void
    {
        $routes = self::$routes[$method] ?? [];
        $url = !str_ends_with($url,"/") ? $url."/":$url;
        foreach ($routes as $route)
        {
            $params = $this->matchPath($route['url'],$url);
            if (is_array($params))
            {
                $this->executeAction($route['action'],$params);
                return;
            }
        }
        throw new \Exception("Page Not Found");
    }

    protected function matchPath(string $pattern, string $url):bool|array
    {
        $params = [];
        $patternSegments = explode('/', $pattern);
        $urlSegments = explode('/', $url);
        if (count($patternSegments) !== count($urlSegments)) {
            return false;
        }
        foreach ($patternSegments as $index => $segment) {
            if (preg_match('/\{(\w+)\}/', $segment,$match))
            {
                $pattern = str_replace($match[0],$urlSegments[$index],$pattern);
                $params[$match[1]] = $urlSegments[$index];
            }
        }
        if ($pattern !== $url)
        {
            return false;
        }
        return $params;
    }

    protected function executeAction(callable|array $action, array $params):void
    {
        switch ($action)
        {
            case is_callable($action):
                call_user_func_array($action,$params);
                break;
            case is_array($action):
                call_user_func_array(array(new $action[0],$action[1]),$params);
                break;
        }
    }
}