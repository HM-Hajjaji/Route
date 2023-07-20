<?php

namespace Route\Utils;

use ReflectionMethod;

trait Route
{
    use Controller;

    /**
     * @throws \ReflectionException
     */
    protected function handleRoute():void
    {
        foreach ($this->handleReflections() as $reflection) {
            $routeController = null;
            foreach ($reflection->getAttributes() as $attribute) {
                if ($attribute->getName() === \Route\Route::class) {
                    $routeController = $attribute->newInstance();
                }
            }

            /**
             * @var ReflectionMethod $method
             */
            foreach ($reflection->getMethods() as $method) {
                /**
                 * @var \Route\Route $routeMethod
                 */
                $routeMethod = null;
                foreach ($method->getAttributes() as $attr) {
                    if ($attr->getName() === \Route\Route::class) {
                        $routeMethod = $attr->newInstance();
                        $routeMethod->setAction([$method->class, $method->name]);
                    }
                }

                if (!$routeMethod) {
                    continue;
                }

                //is the function not public display exception.
                if ($method->getModifiers() != ReflectionMethod::IS_PUBLIC) {
                    throw new \ReflectionException("The modifier of {$method->class}::{$method->getName()} not access in system route");
                }

                $this->handleListRoute($this->prefix($routeController, $routeMethod));
            }
        }
    }

    protected function handleListRoute(\Route\Route $route):void
    {
        if (is_array($route->getMethods()))
        {
            if (isset($route->getMethods()[0]))
            {
                self::$routes[$route->getMethods()[0]][] = $route;
            }
            if (isset($route->getMethods()[1]))
            {
                self::$routes[$route->getMethods()[1]][] = $route;
            }
            return;
        }
        self::$routes[$route->getMethods()][] = $route;
    }

    private function prefix(?\Route\Route $routeController, \Route\Route $routeMethod): \Route\Route
    {
        if ($routeController)
        {
            $routeMethod->setPath($routeController->getPath().$routeMethod->getPath());
        }
        return $routeMethod;
    }

    /**
     * @param string $name
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function path(string $name, array $params = []):string
    {
        foreach (self::$routes as $method) {
            /**
             * @var \Route\Route $route
             */
            foreach ($method as $route) {
                if ($route->getName() == $name) {
                    $path = $route->getPath();
                    foreach (explode("/", $path) as $segment) {
                        if (preg_match('/\{(\w+)\}/', $segment, $match)) {
                            if (!isset($params[$match[1]]))
                            {
                                throw new \Exception(message: "the function {$route->getAction()[0]}::{$route->getAction()[1]} has parameter \${$match[1]} requirement.");
                            }
                            $path = str_replace($match[0], $params[$match[1]], $path);
                            unset($params[$match[1]]);
                        }
                    }
                    if (!empty($params))
                    {
                        $path.='?'.http_build_query($params);
                    }
                    return $path;
                }
            }
        }
        throw new \Exception(message: "The path ($name) not fond.");
    }
}