<?php

namespace Larammerce\AnnotationParser\Tests;


use Larammerce\AnnotationParser\ReflectiveMethod;
use Larammerce\AnnotationParser\Tests\Faker\FakeClassWithAnnotation;
use Larammerce\AnnotationParser\ReflectiveClass;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: arash
 * Date: 2019-03-04
 * Time: 13:22
 */
class ReflectiveModelTest extends TestCase
{

    public function test_reflective_abstraction_has_annotation()
    {
        $className = FakeClassWithAnnotation::class;
        $functionName = "fakeMethodWithAnnotation";

        $this->assertTrue((new ReflectiveClass($className))->hasAnnotation("role"));
        $this->assertFalse((new ReflectiveClass($className))->hasAnnotation("arash"));

        $this->assertTrue((new ReflectiveMethod($className, $functionName))->hasAnnotation("annotation"));
        $this->assertFalse((new ReflectiveMethod($className, $functionName))->hasAnnotation("Annotation"));
        $this->assertFalse((new ReflectiveMethod($className, $functionName))->hasAnnotation("Ali"));
    }

    public function test_annotation_check_property()
    {
        $className = FakeClassWithAnnotation::class;
        $functionName = "fakeMethodWithAnnotation";

        $this->assertTrue((new ReflectiveClass($className))->getAnnotation("role")
            ->checkProperty("enabled", true));
        $this->assertFalse((new ReflectiveClass($className))->getAnnotation("role")
            ->checkProperty("enabled", false));
        $this->assertEquals(true, (new ReflectiveMethod($className, $functionName))->getAnnotation("annotation")
            ->checkProperty("name", "Ali"));
    }

    public function test_annotation_has_property()
    {
        $className = FakeClassWithAnnotation::class;
        $functionName = "fakeMethodWithAnnotation";

        $this->assertTrue((new ReflectiveClass($className))->getAnnotation("role")->hasProperty("enabled"));
        $this->assertFalse((new ReflectiveClass($className))->getAnnotation("role")->hasProperty("ali"));
    }
}