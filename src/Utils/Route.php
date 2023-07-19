<?php

namespace Route\Utils;

trait Route
{
    use Controller;

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
     * @throws \ReflectionException
     */
    protected function handleRoute():void
    {
        foreach ($this->handleReflections() as $reflection)
        {
            $prefix ="";
            if ($reflection->getAttributes())
            {
                $prefix .= $reflection->getAttributes()[0]->newInstance()->getPath();
            }
            foreach ($reflection->getMethods() as $method)
            {
                if (isset($method->getAttributes()[0]))
                {
                    if ($method->getAttributes()[0]->getName() === \Route\Route::class)
                    {
                        /**
                         * @var \Route\Route $route
                         */
                        $route = $method->getAttributes()[0]->newInstance();
                        $this->handleListRoute($route->getName(),$prefix.$route->getPath(),[$method->class,$method->name],$route->getMethods());
                    }
                }
            }
        }
    }
}