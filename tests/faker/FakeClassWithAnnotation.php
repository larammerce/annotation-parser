<?php

namespace Larammerce\AnnotationParser\Tests\Faker;

/**
 * @role(enabled=true)
 * 
 * @author Arash Khajelou
 * @link https://github.com/a-khajelou
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
