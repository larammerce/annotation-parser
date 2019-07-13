<?php

namespace Larammerce\AnnotationParser;

use JsonSerializable;

/**
 * @author Arash Khajelou
 * @link https://github.com/a-khajelou
 * @package Larammerce\AnnotationParser
 */
class Annotation implements JsonSerializable
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var array
     */
    private $properties;

    /**
     * Annotation constructor.
     * @param string $title
     * @param array $properties
     *
     */
    public function __construct(string $title, array $properties = [])
    {
        $this->title = $title;
        $this->properties = $properties;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function put(string $key, $value): bool
    {
        if (!key_exists($key, $this->properties)) {
            $this->properties[$key] = $value;
            return true;
        } else
            return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function drop(string $key): bool
    {
        if (key_exists($key, $this->properties)) {
            unset($this->properties[$key]);
            return true;
        } else
            return false;
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->properties = [];
    }

    /**
     * @return string[]
     */
    public function getPropertyNames(): array
    {
        return array_keys($this->properties);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getProperty(string $name)
    {
        if ($this->hasProperty($name))
            return $this->properties[$name];
        return false;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasProperty(string $name)
    {
        return array_key_exists($name, $this->properties);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function checkProperty(string $key, $value): bool
    {
        return $this->hasProperty($key) and $value == $this->getProperty($key);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'properties' => $this->properties
        ];
    }
}
