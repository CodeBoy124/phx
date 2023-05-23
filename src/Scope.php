<?php

namespace Codeboy124\Phx;

class Scope
{
    public $parts;
    public $type;
    function __construct($type = "css")
    {
        $this->parts = [];
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
    public function AddSrc($url)
    {
        $item = [
            "type" => "url",
            "data" => $url
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
