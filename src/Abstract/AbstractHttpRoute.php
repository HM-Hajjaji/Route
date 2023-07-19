<?php

namespace Route\Abstract;

use Route\Utils\Execute;
use Route\Utils\Request;
use Route\Utils\Route;

abstract class AbstractHttpRoute
{
    use Route,Execute,Request;
    protected string $controller_dir;
    protected static array $routes = [];

}