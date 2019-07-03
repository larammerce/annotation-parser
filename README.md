# annotation-parser

[![Build Status](https://travis-ci.org/larammerce/annotation-parser.svg?branch=master)](https://travis-ci.org/larammerce/annotation-parser)

A PHP [annotation](https://www.geeksforgeeks.org/annotations-in-java/) parser.

## installation
```bash
composer require larammerce/annotation-parser 
```

By default there is no annotation in php language, but for cleaner and meaningful code writing, if you need annotations, then the php annotation parse can be a solution.

usage: Assume that we have a class named `FakeClassWithAnnotation` with a method named `fake_method_with_annotation` as below and there exists a function with name `fake_helper_function` out of the class.
```php
<?php

/**
 * Class FakerClassWithAnnotation
 * @role(enabled=true)
 * @package Larammerce\AnnotationParser\Tests\Faker
 */
class FakeClassWithAnnotation
{
    /**
     * @annotation(name="Ali", username="Ali".fake_helper_function(), roles=['salams', "ali goft: \"che khabar\""],
     *      another_attr=array(1, 2, 3), extras=["role_1" => "role_2"], some_special_id, manager, super_user,
     *      this.is.*="another hard system.")
     * @param string $param1
     * @param bool $param2
     * @return string
     */
    public function fakeMethodWithAnnotation($param1, $param2)
    {
        return "I am fake";
    }
}
```

```php
<?php

function fake_helper_function()
{
    return "this is fake helper function";
}
```

We want to parse the data in `@role`, `@annotation` or every other annotation in phpdoc section.
```php
<?php
use Larammerce\AnnotationParser\ReflectiveClass;
use Larammerce\AnnotationParser\ReflectiveMethod;
use Larammerce\AnnotationParser\Tests\Faker\FakeClassWithAnnotation;

        
$class_name = FakeClassWithAnnotation::class;
$function_name = "fakeMethodWithAnnotation";

$reflective_class = new ReflectiveClass($class_name); //construct the Reflective class.
$reflective_method = new ReflectiveMethod($class_name, $function_name); //construct the reflective method.


$reflective_class->getComment(); //returns the phpdoc on top of class.
$reflective_method->getComment(); //returns the phpdoc on top of method.

$reflective_class->getAnnotations(); //returns a list of annotations.
$reflective_class->hasAnnotation("specific_annotation"); //checks if specific annotations exists or not.
$reflective_class->getAnnotation(("specific_annotation")); //returns the specific annotation with passed title.

```