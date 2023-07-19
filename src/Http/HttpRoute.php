<?php

namespace Route\Http;

use Route\Abstract\AbstractHttpRoute;

class HttpRoute extends AbstractHttpRoute
{
    protected string $controller_dir;

    /**
     * @throws \ReflectionException
     */
    public function __construct(string $controller_dir)
    {
        $this->controller_dir = $controller_dir;
        $this->handleRoute();
    }
    /**
     * @throws \Exception
     */
    public function resolve(): void
    {
        $this->execute($this->getMethod(),$this->getPathInfo());
    }
}