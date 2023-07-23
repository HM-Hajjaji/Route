<?php

namespace Route\Utils;

use ReflectionClass;

trait Controller
{
    /**
     * @throws \Exception
     */
    protected function handleControllers(string $path):array
    {
        if (!is_dir($path))
        {
            throw new \Exception("Not fond $path");
        }

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
     * @throws \ReflectionException
     */
    protected function handleReflections():array
    {
        $reflections = [];
        foreach ($this->handleControllers($this->controller_dir) as $controller)
        {
            $reflections[] = new ReflectionClass($controller);
        }
        return $reflections;
    }
}