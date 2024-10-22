<?php

namespace Larammerce\AnnotationParser;

/**
 * @author Arash Khajelou
 * @link https://github.com/a-khajelou
 * @package Larammerce\AnnotationParser
 */
class AnnotationParser
{
    /**
     * @var string
     */
    private $php_doc;
    /**
     * @var string[]
     */
    private $titles;

    private static $SCOPE_OPEN = ["[", "("];
    private static $SCOPE_CLOSE = ["]", ")"];
    private static $STRING_SCOPE = ["\"", "'"];
    private static $WHITE_SPACE = [" ", "*", "\t"];
    private static $EQUALITY_SIGN = "=";
    private static $SEPARATOR_SIGN = ",";
    private static $TITLE_PATTERN = "/\@([a-zA-Z_]{2,20})\(/";
    private static $VALID_KEY_PATTERN = "/[^A-Za-z0-9_]/";
    private static $VALID_KEY_EXTRA_PATTERN = "/[^\.\*\-]/";

    /**
     * PhpDocParser constructor.
     * @param $php_doc
     */
    public function __construct(string $php_doc)
    {
        $this->php_doc = $php_doc;
        $this->makeClean();
        $this->process();
    }

    /**
     * @return void
     */
    private function makeClean(): void
    {
        $this->php_doc = str_replace("\n", "", $this->php_doc);
    }

    /**
     * @return void
     */
    private function process(): void
    {
        preg_match_all(static::$TITLE_PATTERN, $this->php_doc, $matches);
        if (count($matches) === 2) {
            $this->titles = $matches[1];
        }
    }

    /**
     * @return string[]
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @param string $title
     * @return array
     * @throws AnnotationBadKeyException
     * @throws AnnotationBadScopeException
     */
    public function parseValue(string $title): array
    {
        $in_scope = false;
        $scope_depth = 0;
        $in_string = false;
        $back_slash_detected = false;
        $white_space_detected = false;
        $result = [];
        $parsing_key = true;

        $php_doc_array = str_split($this->php_doc);
        $php_doc_size = strlen($this->php_doc);
        $start_point = strpos($this->php_doc, "@" . $title);
        if ($start_point === false) {
            return [];
        }
        $start_point += (strlen($title) + 1);

        if ($php_doc_array[$start_point] !== "(")
            return [];

        $current_pointer = $start_point + 1;
        $current_key = "";
        $current_value = "";

        while ($php_doc_array[$current_pointer] != ")" or $in_string !== false or $in_scope !== false) {
            $current_char = $php_doc_array[$current_pointer];

            if ($in_string === false and $in_scope === false and $current_char === static::$SEPARATOR_SIGN) {
                if (strlen($current_key) >= 2) {
                    $result[$current_key] = $current_value;
                    $current_key = "";
                    $current_value = "";
                    $parsing_key = true;
                } else {
                    throw new AnnotationBadKeyException("The annotation should have minimum length of 2");
                }
            } else if ($parsing_key) {
                if ($current_char === static::$EQUALITY_SIGN) {
                    $parsing_key = false;
                } else if (
                    !preg_match(static::$VALID_KEY_PATTERN, $current_char) or ((!preg_match(static::$VALID_KEY_EXTRA_PATTERN, $current_char)) and strlen($current_key) > 0)
                ) {
                    $current_key .= $current_char;
                } else if (strlen($current_key) !== 0) {
                    throw new AnnotationBadKeyException("The annotation key should not pass " .
                        static::$VALID_KEY_PATTERN . ". It contains '{$current_char}'.");
                }
            } else {
                if ($in_string !== false) {
                    if ($back_slash_detected) {
                        $back_slash_detected = false;
                    } else if (in_array($current_char, static::$STRING_SCOPE)) {
                        if ($in_string === $current_char)
                            $in_string = false;
                    } else if ($current_char === "\\")
                        $back_slash_detected = true;
                } else if (in_array($current_char, static::$STRING_SCOPE)) {
                    $in_string = $current_char;
                } else if (array_search($current_char, static::$SCOPE_OPEN) !== false) {
                    if ($in_scope !== false) {
                        if ($in_scope === $current_char) {
                            $scope_depth += 1;
                        }
                    } else {
                        $in_scope = $current_char;
                        $scope_depth += 1;
                    }
                } else if (($scope_char_pos = array_search($current_char, static::$SCOPE_CLOSE)) !== false) {
                    $scope_char_open = static::$SCOPE_OPEN[$scope_char_pos];

                    if ($in_scope !== false) {
                        if ($in_scope === $scope_char_open) {
                            $scope_depth -= 1;
                            if ($scope_depth === 0) {
                                $in_scope = false;
                            }
                        }
                    } else {
                        throw new AnnotationBadScopeException("The annotation scope closed with " .
                            "'{$current_char}', although there is no start scope.");
                    }
                } else if (array_search($current_char, static::$WHITE_SPACE) !== false) {
                    $white_space_detected = true;
                }

                if ($white_space_detected)
                    $white_space_detected = false;
                else
                    $current_value .= $current_char;
            }

            $current_pointer++;
            if ($current_pointer >= $php_doc_size)
                throw new AnnotationBadScopeException("The annotation should have end scope character" .
                    " like ')' or ']' ...");
        }

        if (strlen($current_key) >= 2) {
            $result[$current_key] = $current_value;
        }

        return $result;
    }
}
