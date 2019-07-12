<?php

/**
 * Created by PhpStorm.
 * User: arash
 * Date: 7/13/16
 * Time: 1:42 PM
 */

namespace Larammerce\AnnotationParser;


use Exception;

abstract class ReflectiveAbstraction
{
    /**
     * @var Annotation[]
     */
    private $annotations;
    /**
     * @var AnnotationParser
     */
    private $annotation_parser;

    /**
     * @return string
     */
    public abstract function getComment(): string;

    /**
     * ReflectiveAbstraction constructor.
     * @param array $needed_titles
     * @throws AnnotationBadKeyException
     * @throws AnnotationBadScopeException
     * @throws AnnotationSyntaxException
     */
    public function __construct(array $needed_titles = [])
    {
        $this->annotations = [];
        $this->annotation_parser = new AnnotationParser($this->getComment());

        if (count($needed_titles) === 0)
            $needed_titles = $this->annotation_parser->getTitles();

        foreach ($needed_titles as $annotation_title) {
            $this->annotations[$annotation_title] = $this->createAnnotationByTitle($annotation_title);
        }
    }

    /**
     * @param bool $needs_array
     * @return Annotation[]
     */
    public function getAnnotations(bool $needs_array = false): array
    {
        if ($needs_array)
            return array_values($this->annotations);
        return $this->annotations;
    }

    /**
     * @param Annotation[] $annotations
     * @return void
     */
    public function setAnnotations(array $annotations): void
    {
        $this->annotations = $annotations;
    }

    /**
     * @param string $title
     * @return Annotation|bool
     * @throws AnnotationBadKeyException
     * @throws AnnotationBadScopeException
     * @throws AnnotationSyntaxException
     */
    private function createAnnotationByTitle(string $title): Annotation
    {
        return new Annotation($title, $this->parseProperties($title));
    }

    /**
     * @param string $title
     * @return string[]
     * @throws AnnotationBadKeyException
     * @throws AnnotationBadScopeException
     * @throws AnnotationSyntaxException
     */
    private function parseProperties(string $title): array
    {
        $result_array = [];
        try {
            $properties = $this->annotation_parser->parseValue($title);
            foreach ($properties as $key => $value) {
                $evaluated_value = "";
                if (strlen($value) > 0)
                    try {
                        eval("\$evaluated_value = {$value};");
                    } catch (Exception $e) {
                        throw new AnnotationSyntaxException("Syntax error in : `{$value}`");
                    }

                $result_array[$key] = $evaluated_value;
            }
        } catch (AnnotationBadKeyException $e) {
            throw new AnnotationBadKeyException("Bad Key passed for @{$title}:\n{$e->getMessage()}");
        }

        return $result_array;
    }

    /**
     * @param string $title
     * @return bool
     */
    public function hasAnnotation(string $title): bool
    {
        return array_key_exists($title, $this->annotations);
    }


    /**
     * @param string $title
     * @return Annotation
     * @throws AnnotationNotFoundException
     */
    public function getAnnotation(string $title): Annotation
    {
        if ($this->hasAnnotation($title))
            return $this->annotations[$title];
        throw new AnnotationNotFoundException("There is no annotation named '{$title}' in \n{$this->getComment()}");
    }

    /**
     * @return AnnotationParser
     */
    public function getAnnotationParser(): AnnotationParser
    {
        return $this->annotation_parser;
    }
}
