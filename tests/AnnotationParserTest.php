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
        $method_comment = (new ReflectiveMethod(
            FakeClassWithAnnotation::class,
            "fakeMethodWithAnnotation"
        ))->getComment();
        $class_comment = (new ReflectiveClass(FakeClassWithAnnotation::class))->getComment();

        $method_annotation_parser = new AnnotationParser($method_comment);
        $class_annotation_parser = new AnnotationParser($class_comment);
        $method_titles = $method_annotation_parser->getTitles();
        $class_titles = $class_annotation_parser->getTitles();
        sort($method_titles);
        sort($class_titles);

        $expected_result_method = ['annotation'];
        $expected_result_class = ['role'];
        sort($expected_result_method);
        sort($expected_result_class);
        $this->assertEquals($method_titles, $expected_result_method);
        $this->assertEquals($class_titles, $expected_result_class);
    }

    public function test_annotation_parser_parse_value()
    {

        $method_comment = (new ReflectiveMethod(
            FakeClassWithAnnotation::class,
            "fakeMethodWithAnnotation"
        ))->getComment();
        $class_comment = (new ReflectiveClass(FakeClassWithAnnotation::class))->getComment();

        $method_annotation_parser = new AnnotationParser($method_comment);
        $method_values = $method_annotation_parser->parseValue("annotation");

        $expected_result_method = [
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

        foreach ($expected_result_method["annotation"] as $key => $value) {
            $this->assertArrayHasKey($key, $method_values);
            $this->assertEquals($value, $method_values[$key]);
        }

        $class_annotation_parser = new AnnotationParser($class_comment);
        $class_values = $class_annotation_parser->parseValue("role");

        $expected_result_class = [
            "role" => [
                "enabled" => "true"
            ]
        ];

        foreach ($expected_result_class["role"] as $key => $value) {
            $this->assertArrayHasKey($key, $class_values);
            $this->assertEquals($value, $class_values[$key]);
        }
    }
}
