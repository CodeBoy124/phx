<?php

namespace Codeboy124\Phx;

require __DIR__ . "/../vendor/autoload.php";

use Pharse;

class Phx
{
    private static function formatAttributeValue($value)
    {
        if (is_bool($value) || $value == "true" || $value == "false") {
            return null;
        }
        if (is_string($value)) {
            return "\"$value\"";
        }
        if (is_numeric($value)) {
            return $value;
        }
        throw new \Exception("Data $value is of unknown type that can't be formatted for an attribute");
    }
    public static function Attributes($attributes)
    {
        $outputParts = [];
        foreach ($attributes as $attributeName => $attributeValue) {
            $formattedValue = self::formatAttributeValue($attributeValue);
            if ($formattedValue == null) {
                if (($attributeValue == true || $attributeValue == "true") && $attributeValue != "false") {
                    array_push($outputParts, $attributeName);
                }
                continue;
            }
            array_push($outputParts, "$attributeName=$formattedValue");
        }
        return implode(" ", $outputParts);
    }
    public static function createElement($type, $attributes = [], $children = [])
    {
        if (is_callable($type)) {
            return $type($attributes, $children);
        }
        if (count($attributes) > 0 && count($children) > 0) {
            $attributeString = self::Attributes($attributes);
            $childrenJoined = implode("", $children);
            return "<$type $attributeString>$childrenJoined</$type>";
        }
        if (count($attributes) > 0 && count($children) < 1) {
            $attributeString = self::Attributes($attributes);
            return "<$type $attributeString />";
        }
        if (count($attributes) < 1 && count($children) > 0) {
            $childrenJoined = implode("", $children);
            return "<$type>$childrenJoined</$type>";
        }
        if (count($attributes) < 1 && count($children) < 1) {
            $attributeString = self::Attributes($attributes);
            $childrenJoined = implode("", $children);
            return "<$type/>";
        }
        throw new \Exception("How did you get this error by making an element?");
    }
    private static function Convert($child)
    {
        if ($child->tag == "~text~") {
            return trim($child->text);
        }
        $innerChildren = [];
        foreach ($child->children as $innerChild) {
            array_push($innerChildren, self::Convert($innerChild));
        }
        if ($child->tag == "~root~") {
            return implode("", $innerChildren);
        }
        return self::createElement($child->tag, $child->attributes, $innerChildren);
    }
    public static function Run($content)
    {
        $html = Pharse::str_get_dom($content);
        return self::Convert($html);
    }
}
