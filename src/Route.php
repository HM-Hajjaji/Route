<?php
namespace Route;
#[\Attribute]
final class Route
{
    private string $path;
    private string $name;
    private string|array $methods;
    private array $action;

    /**
     * @param string $name
     * @param string $path
     * @param string|array $methods
     */
    public function __construct(string $path,string $name="", string|array $methods="GET")
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
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = !str_ends_with($path,"/") ? $path."/":$path;
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

    /**
     * @return array
     */
    public function getAction(): array
    {
        return $this->action;
    }

    /**
     * @param array $action
     */
    public function setAction(array $action): void
    {
        $this->action = $action;
    }
}