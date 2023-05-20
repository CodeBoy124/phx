<?php
require __DIR__ . "/../vendor/autoload.php";

use \Codeboy124\Phx\Phx;

function MyLayout($attributes, $children)
{
    return Phx::createElement("html", [], [
        Phx::createElement("head", [], [
            Phx::createElement("title", [], [
                $attributes["title"]
            ])
        ]),
        Phx::createElement("body", [], [
            Phx::createElement("main", [], $children)
        ])
    ]);
}

function App()
{
    return Phx::createElement("MyLayout", ["title" => "Test page"], [
        Phx::createElement("h1", [
            "style" => "color: green;"
        ], [
            "Hello"
        ])
    ]);
}
echo App();
