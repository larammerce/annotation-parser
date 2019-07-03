<?php
/**
 * Created by PhpStorm.
 * User: arash
 * Date: 7/13/16
 * Time: 1:41 PM
 */

namespace Larammerce\AnnotationParser;


use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class ReflectiveClass extends ReflectiveAbstraction
{
    /**
     * @var string
     */
    private $class_name;
    /**
     * @var ReflectionClass
     */
    private $reflection_class;

    /**
     * ReflectiveClass constructor.
     * @param string $class_name
     * @throws AnnotationBadKeyException
     * @throws AnnotationBadScopeException
     * @throws AnnotationSyntaxException
     * @throws ReflectionException
     */
    public function __construct(string $class_name)
    {
        $this->class_name = $class_name;
        $this->reflection_class = new ReflectionClass($class_name);
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->reflection_class->getDocComment();
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
     * @return ReflectionClass
     */
    public function getReflectionClass(): ReflectionClass
    {
        return $this->reflection_class;
    }

    /**
     * @param ReflectionClass $reflection_class
     */
    public function setReflectionClass(ReflectionClass $reflection_class): void
    {
        $this->reflection_class = $reflection_class;
    }

    /**
     * @return ReflectionMethod[]
     */
    private function reflectionMethods(): array
    {
        return $this->reflection_class->getMethods();
    }
}