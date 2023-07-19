<?php
namespace Route;
#[\Attribute]
final class Route
{
    private string $path;
    private string $name;
    private string|array $methods;

    /**
     * @param string $name
     * @param string $path
     * @param string|array $methods
     */
    public function __construct(string $path,string $name ="", string|array $methods="GET")
    {
        $this->path = $path;
        $this->name = $name;
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