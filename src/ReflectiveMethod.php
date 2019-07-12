<?php

/**
 * Created by PhpStorm.
 * User: arash
 * Date: 7/13/16
 * Time: 1:41 PM
 */

namespace Larammerce\AnnotationParser;


use ReflectionException;
use ReflectionMethod;

class ReflectiveMethod extends ReflectiveAbstraction
{
    /**
     * @var string
     */
    private $class_name;
    /**
     * @var string
     */
    private $method_name;
    /**
     * @var ReflectionMethod
     */
    private $reflection_method;

    /**
     * ReflectiveMethod constructor.
     * @param string $class_name
     * @param $method_name
     * @throws AnnotationBadKeyException
     * @throws AnnotationBadScopeException
     * @throws AnnotationSyntaxException
     * @throws ReflectionException
     */
    public function __construct(string $class_name, string $method_name)
    {
        $this->class_name = $class_name;
        $this->method_name = $method_name;
        $this->reflection_method = new ReflectionMethod($this->class_name, $this->method_name);
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->reflection_method->getDocComment();
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->class_name;
    }

    /**
     * @param string $class_name
     */
    public function setClassName(string $class_name): void
    {
        $this->class_name = $class_name;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->method_name;
    }

    /**
     * @param string $method_name
     */
    public function setMethodName(string $method_name): void
    {
        $this->method_name = $method_name;
    }

    /**
     * @return ReflectionMethod
     */
    public function getReflectionMethod(): ReflectionMethod
    {
        return $this->reflection_method;
    }

    /**
     * @param ReflectionMethod $reflection_method
     */
    public function setReflectionMethod(ReflectionMethod $reflection_method)
    {
        $this->reflection_method = $reflection_method;
    }
}
