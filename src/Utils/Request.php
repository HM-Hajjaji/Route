<?php

namespace Route\Utils;

trait Request
{
    public function getMethod():string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function getPathInfo():string
    {
        return $_SERVER['PATH_INFO']??"/";
    }
}