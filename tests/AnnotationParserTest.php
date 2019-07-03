<?php

namespace Larammerce\AnnotationParser\Tests;


use Larammerce\AnnotationParser\Tests\Faker\FakeClassWithAnnotation;
use Larammerce\AnnotationParser\AnnotationParser;
use Larammerce\AnnotationParser\ReflectiveClass;
use Larammerce\AnnotationParser\ReflectiveMethod;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: arash
 * Date: 1/13/19
 * Time: 11:00 AM
 */
class AnnotationParserTest extends TestCase
{
    public function test_annotation_parser_get_titles()
    {
        $methodComment = (new ReflectiveMethod(FakeClassWithAnnotation::class,
            "fakeMethodWithAnnotation"))->getComment();
        $classComment = (new ReflectiveClass(FakeClassWithAnnotation::class))->getComment();

        $methodAnnotationParser = new AnnotationParser($methodComment);
        $classAnnotationParser = new AnnotationParser($classComment);
        $methodTitles = $methodAnnotationParser->getTitles();
        $classTitles = $classAnnotationParser->getTitles();
        sort($methodTitles);
        sort($classTitles);

        $expectedResultMethod = ['annotation'];
        $expectedResultClass = ['role'];
        sort($expectedResultMethod);
        sort($expectedResultClass);
        $this->assertEquals($methodTitles, $expectedResultMethod);
        $this->assertEquals($classTitles, $expectedResultClass);
    }

    public function test_annotation_parser_parse_value()
    {

        $methodComment = (new ReflectiveMethod(FakeClassWithAnnotation::class,
            "fakeMethodWithAnnotation"))->getComment();
        $classComment = (new ReflectiveClass(FakeClassWithAnnotation::class))->getComment();

        $methodAnnotationParser = new AnnotationParser($methodComment);
        $methodValues = $methodAnnotationParser->parseValue("annotation");

        $expectedResultMethod = [
            "annotation" => [
                "name" => "\"Ali\"",
                "username" => "\"Ali\".fake_helper_function()",
                "roles" => "['salams',\"ali goft: \\\"che khabar\\\"\"]",
                "another_attr" => "array(1,2,3)",
                "extras" => "[\"role_1\"=>\"role_2\"]",
                "some_special_id" => "",
                "manager" => "",
                "super_user" => "",
                "this.is.*" => "\"another hard system.\""
            ]
        ];

        foreach ($expectedResultMethod["annotation"] as $key => $value) {
            $this->assertArrayHasKey($key, $methodValues);
            $this->assertEquals($value, $methodValues[$key]);
        }

        $classAnnotationParser = new AnnotationParser($classComment);
        $classValues = $classAnnotationParser->parseValue("role");

        $expectedResultClass = [
            "role" => [
                "enabled" => "true"
            ]
        ];

        foreach ($expectedResultClass["role"] as $key => $value) {
            $this->assertArrayHasKey($key, $classValues);
            $this->assertEquals($value, $classValues[$key]);
        }
    }
}