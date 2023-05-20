<?php
require __DIR__ . "/../vendor/autoload.php";

use \Codeboy124\Phx\Phx;

function MyLayout($attributes, $children)
{
    $title = $attributes["title"];
    $joinedChildren = implode("", $children);
    return Phx::Run(<<<HTML
        <html>
            <head>
                <title>$title</title>
            </head>
            <body>
                <main>$joinedChildren</main>
            </body>
        </html>
    HTML);
}

function App()
{
    return Phx::Run(<<<HTML
        <MyLayout title='Test page'>
            <h1 style='color: green;'>Hello</h1>
        </MyLayout>
    HTML);
}
echo App();
