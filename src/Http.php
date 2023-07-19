<?php

namespace Route;

use Symfony\Component\HttpFoundation\Request;

class Http extends Kernel
{
    protected string $controller_dir;
    private Request $request;

    /**
     * @param string $controller_dir
     * @throws \ReflectionException
     */
    public function __construct(string $controller_dir)
    {
        $this->controller_dir = $controller_dir;
        $this->request = Request::createFromGlobals();
        $this->handleRoute();
    }
    
    /**
     * @throws \ReflectionException
     */
    public function handleRoute():void
    {
        foreach ($this->handleReflections() as $reflection)
        {
            foreach ($reflection->getMethods() as $method)
            {
                if (isset($method->getAttributes()[0]))
                {
                    if ($method->getAttributes()[0]->getName() === Route::class)
                    {
                        /**
                         * @var Route $route
                         */
                        $route = $method->getAttributes()[0]->newInstance();
                        $this->handleListRoute($route->getName(),$route->getPath(),[$method->class,$method->name],$route->getMethods());
                    }
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function resolve(): void
    {
        $this->execute($this->request->getMethod(),$this->request->getPathInfo());
    }
}