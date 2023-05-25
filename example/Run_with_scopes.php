<?php
require("vendor/autoload.php");

use Codeboy124\Phx\Phx;
use Codeboy124\Phx\Scope;

include(__DIR__ . "/../src/ScopeTag.php");

$js = new Scope("http://localhost:3000/", "js");
$css = new Scope("http://localhost:3000/", "css");

function ConfirmButton($attributes)
{
    global $js;
    global $css;
    $attributeString = Phx::Attributes($attributes);
    $js->Add("
        function ConfirmButton_someClickHandler(){
            console.log(\"confirmed\");
        }
    ");
    $css->Add("
        .danger {
            color: white;
            background-color: red;
        }
    ");
    return Phx::Run("
        <button class='danger' $attributeString>Confirm</button>
    ");
}

function Layout($_, $children)
{
    return Phx::Run("
        <html>
            <head>
                <Scopes from='css' />
            </head>
            <body>
                " . implode("", $children) . "
                <Scopes from='js' />
            </body>
        </html>
    ");
}

function App()
{
    return Phx::Run("
        <Layout>
            <h1>Click 'Confirm'</h1>
            <ConfirmButton onclick='ConfirmButton_someClickHandler()' />
            <ConfirmButton onclick='ConfirmButton_someClickHandler()' />
        </Layout>
    ");
}

echo App();
