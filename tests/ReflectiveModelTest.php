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
        $class_name = FakeClassWithAnnotation::class;
        $function_name = "fakeMethodWithAnnotation";

        $this->assertTrue((new ReflectiveClass($class_name))->hasAnnotation("role"));
        $this->assertFalse((new ReflectiveClass($class_name))->hasAnnotation("arash"));

        $this->assertTrue((new ReflectiveMethod($class_name, $function_name))->hasAnnotation("annotation"));
        $this->assertFalse((new ReflectiveMethod($class_name, $function_name))->hasAnnotation("Annotation"));
        $this->assertFalse((new ReflectiveMethod($class_name, $function_name))->hasAnnotation("Ali"));
    }

    public function test_annotation_check_property()
    {
        $class_name = FakeClassWithAnnotation::class;
        $function_name = "fakeMethodWithAnnotation";

        $this->assertTrue((new ReflectiveClass($class_name))->getAnnotation("role")
            ->checkProperty("enabled", true));
        $this->assertFalse((new ReflectiveClass($class_name))->getAnnotation("role")
            ->checkProperty("enabled", false));
        $this->assertEquals(true, (new ReflectiveMethod($class_name, $function_name))->getAnnotation("annotation")
            ->checkProperty("name", "Ali"));
    }

    public function test_annotation_has_property()
    {
        $class_name = FakeClassWithAnnotation::class;
        $function_name = "fakeMethodWithAnnotation";

        $this->assertTrue((new ReflectiveClass($class_name))->getAnnotation("role")->hasProperty("enabled"));
        $this->assertFalse((new ReflectiveClass($class_name))->getAnnotation("role")->hasProperty("ali"));
    }

    public function test_reflective_method_construct_with_action()
    { 
        $action = FakeClassWithAnnotation::class."@fakeMethodWithAnnotation";

        $this->assertEquals(true, (ReflectiveMethod::withAction($action))->getAnnotation("annotation")
            ->checkProperty("name", "Ali"));
    }
}
