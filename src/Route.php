<?php
namespace Route;
#[\Attribute]
final class Route
{
    private string $name;
    private string $path;
    private string|array $methods;

    /**
     * @param string $name
     * @param string $path
     * @param string|array $methods
     */
    public function __construct(string $name, string $path, string|array $methods="GET")
    {
        $this->name = $name;
        $this->path = $path;
        $this->methods = $methods;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array|string
     */
    public function getMethods(): array|string
    {
        return $this->methods;
    }
}