<?php

namespace Route\Utils;

trait Execute
{
    /**
     * @throws \Exception
     */
    protected function execute(string $method, string $url):void
    {
        $routes = self::$routes[$method] ?? [];
        $url = !str_ends_with($url,"/") ? $url."/":$url;
        foreach ($routes as $route)
        {
            $params = $this->matchPath($route->getPath(),$url);
            if (is_array($params))
            {
                $this->executeAction($route->getAction(),$params);
                return;
            }
        }
        throw new \Exception("Route $method ($url) not find");
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