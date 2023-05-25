<?php

namespace Codeboy124\Phx;

class Scope
{
    public $parts;
    public $root;
    public $type;
    function __construct($root = "", $type = "css")
    {
        $this->parts = [];
        $this->root = $root;
        $this->type = $type;
    }
    public function Add($content)
    {
        $item = [
            "type" => "text",
            "data" => $content
        ];
        if (in_array($item, $this->parts)) return;
        array_push($this->parts, $item);
    }
    private static function isAbsoluteUrl($url)
    {
        return isset(parse_url($url)["host"]);
    }
    private static function combinePaths($path1, $path2)
    {
        if (substr($path1, -1) == "/" && $path2[0] == "/") {
            return $path1 . substr($path2, 1);
        }
        if (substr($path1, -1) == "/" xor $path2[0] == "/") {
            return $path1 . $path2;
        }
        return $path1 . "/" . $path2;
    }
    public function AddSrc($url)
    {
        $itemUrl = $url;
        if (!self::isAbsoluteUrl($url)) {
            $itemUrl = self::combinePaths($this->root, $url);
        }
        $item = [
            "type" => "url",
            "data" => $itemUrl
        ];
        if (in_array($item, $this->parts)) return;
        array_push($this->parts, $item);
    }
    public function Read()
    {
        $outputParts = [];
        foreach ($this->parts as $part) {
            switch ($this->type) {
                case "js":
                    if ($part["type"] == "text") {
                        array_push($outputParts, "<script>" . $part["data"] . "</script>");
                    } else if ($part["type"] == "url") {
                        array_push($outputParts, "<script src='" . $part["data"] . "'></script>");
                    } else {
                        throw new \Exception("Unknown scope type, " . $part["type"]);
                    }
                    break;
                case "css":
                    if ($part["type"] == "text") {
                        array_push($outputParts, "<style>" . $part["data"] . "</style>");
                    } else if ($part["type"] == "url") {
                        array_push($outputParts, "<link rel='stylesheet' href='" . $part["data"] . "'></script>");
                    } else {
                        throw new \Exception("Unknown scope type, " . $part["type"]);
                    }
                    break;
            }
        }
        return implode("", $outputParts);
    }
}
