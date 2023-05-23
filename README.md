# Phx Package Documentation

## Introduction

The Phx package is a PHP Composer package that allows you to use a JSX-like syntax in your PHP applications. This package contains some functions that enable you to create and render components in a more declarative and intuitive way.

## Installation

To install the Phx package, you need to have Composer installed in your PHP environment. Then, you can run the following command:

```
composer require codeboy124/phx
```

## Usage

### `Phx::Run`

The `Phx::Run` function is used to execute a string that represents JSX-like code. It takes the string as an argument and renders the corresponding component.

#### **paramaters**
* $content (string): The phx code to turn into regular html

#### **returns**
string: The content as a string containing html

#### **example**

```php
<?php
require("vendor/autoload.php");

use Codeboy124\Phx\Phx;

function Greet($props){
    $name = $props["name"];
    return Phx::Run("
        <h1>Hello, <i>$name</i></h1>
    ");
}

function Layout($props, $children){
    $childrenJoined = implode("", $children);
    return Phx::Run("
        <main>
            $childrenJoined
        </main>
    ");
}

function App() {
    return Phx::Run("
        <Layout>
            <Greet name='user12' />
            <p>
                Some text paragraph...
            </p>
        </Layout>
    ")
}

echo App();
```

### `Phx::createElement`

The `Phx::createElement` function is similar to the `createElement` function in React. It allows you to create an html element with the specified tag name, attributes, and children.
Internally this is what `Phx::Run` uses to build the html after parsing the phx.
If you want to improve performance for production there might eventually be a program that convert `Phx::Run` into a system with `Phx::createElement` to remove the time required to parse the phx code, but this is not yet implemented.

Since `Phx::createElement` and `Phx::Run` are nearly the same you can also combine them if you want to.

#### **paramaters**
* $type (string | callback): The string containing the html element or the callback that is a component
* $attributes (Associative array, optional): The array with keys that are the attributes for the element/component
* $children (Indexed array, optional): An array with all the child elements/components for the element/component

#### **returns**
string: Basically the same as Phx::Run, so a string that contains the converted phx code

#### **example**

```php
<?php
require("vendor/autoload.php");

use Codeboy124\Phx\Phx;

function Greet($props){
    $name = $props["name"];
    return Phx::createElement("h1", [], [
        Phx::createElement("i", [], [$name])
    ]);
}

function App() {
    return Phx::createElement("div", [], [
        Phx::createElement("Greet", [
            "name" => "user12"
        ]),
        Phx::createElement("p", [], [
            "Some text paragraph"
        ])
    ]);
}

echo App();
```

### `Phx::Attributes`

The `Phx::Attributes` function helps format attributes into a string that can be used inside the `Phx::Run` function. It takes an associative array of attributes and returns a formatted string.

#### **paramaters**
* $attributes (Associative array): The array with keys that are the attributes for the element/component

#### **returns**
string: A formatted string that follows the following format 'mynumber=7 mystring="text"'

#### **example**

```php
<?php
require("vendor/autoload.php");

use Codeboy124\Phx\Phx;

function ConfirmButton($attributes){
    $attributeString = Phx::Attributes($attributes);
    return Phx::Run("
        <button $attributeString>Confirm</button>
    ");
}

function App(){
    return Phx::Run("
        <div>
            <h1>Click 'Confirm'</h1>
            <ConfirmButton onclick='someClickHandler()' />
        </div>
    ");
}

echo App();
```

### `Scope`

The `Scope` class manages all scoped js or css.
Please create a GLOBAL instance of `Scope`

#### **constructor paramaters**
* $type ("css" or "js", default is "css"): The kind of scoped content. This can be either "css" or "js"

#### **example**
```php
<?php
require("vendor/autoload.php");

use Codeboy124\Phx\Scope;
$js = new Scope("js");
$css = new Scope(); // or `new Scope("css")`
```

### `Scope->Add`
A method of the `Scope` class that adds some text content to your scopes.
Be aware that it does not modify the source material and therefor be carefull that you don't define the same function in 2 places

#### **paramaters**
* $content (string): The text content to add

#### **returns**
Nothing, this is a void

#### **example**

```php
<?php
require("vendor/autoload.php");

use Codeboy124\Phx\Phx;
use Codeboy124\Phx\Scope;

$js = new Scope("js");

function HelloWorldButton(){
    global $js;
    $js->Add("
        function helloWorld(){
            console.log('Hello, World!');
        }
    ");
    return Phx::Run("
        <button onclick='helloWorld()'>Click me</button>
    ")
}

// etc

```

### `Scope->AddSrc`
A method of the `Scope` class that adds some url to your scopes

#### **paramaters**
* $url (string): The url of the source

#### **returns**
Nothing, this is a void

#### **example**

```php
<?php
require("vendor/autoload.php");

use Codeboy124\Phx\Phx;
use Codeboy124\Phx\Scope;

$js = new Scope("js");

function ScopedSourceButton(){
    global $js;
    $js->AddSrc("https://some.script.src/lib.min.js");
    return Phx::Run("
        <button onclick='someFunction()'>Click me</button>
    ")
}

// etc

```

### `Scope->Read`
A method of the `Scope` class that converts all the sources to actual html.
This is usually done by adding the builtin `Scopes` component that I will explain later.

#### **paramaters**
None

#### **returns**
string: Generated `script`, `style` or `link` tags

#### **example**

```php
<?php
require("vendor/autoload.php");

use Codeboy124\Phx\Phx;
use Codeboy124\Phx\Scope;

$js = new Scope("js");

function HelloWorldButton(){
    global $js;
    $js->Add("
        function helloWorld(){
            console.log('Hello, World!');
        }
    ");
    return Phx::Run("
        <button onclick='helloWorld()'>Click me</button>
    ")
}

// DOES NOT WORK! The Read method is called before the HelloWorldButton adds its scopes
function App(){
    $scopes = $js->Read();
    return Phx::Run("
        <main>
            <HelloWorldButton />
        </main>
        $scopes
    ")
}

// DOES WORK! The children are compiled first before they are passed to this function
function Layout($_, $children){
    $childrenJoined = implode("", $children);
    $scopes = $js->Read();
    return Phx::Run("
        <main>
            $childrenJoined
        </main>
        $scopes
    ")
}
function App(){
    return Phx::Run("
        <HelloWorldButton />
    ");
}

echo App();

```

### `Scopes`
A component that does the same as the `Read` method of `Scope`, but uses a component instead.

#### **attributes**
from (string): The name (without the '$' character) of your `Scope` instance

#### **returns**
string: Generated `script`, `style` or `link` tags

#### **example**

```php
<?php
require("vendor/autoload.php");

use Codeboy124\Phx\Phx;
use Codeboy124\Phx\Scope;

include("vendor/codeboy124/phx/ScopeTag.php"); // If this does not work just search for where you can find the `ScopeTag.php` file

$js = new Scope("js");

function HelloWorldButton(){
    global $js;
    $js->Add("
        function helloWorld(){
            console.log('Hello, World!');
        }
    ");
    return Phx::Run("
        <button onclick='helloWorld()'>Click me</button>
    ")
}

// DOES NOT WORK! The Read method is called before the HelloWorldButton adds its scopes
function App(){
    return Phx::Run("
        <Scopes from='js' />
        <main>
            <HelloWorldButton />
        </main>
    ")
}

// DOES WORK! The scopes are read after the HelloWorldButton's scopes were added
function App(){
    return Phx::Run("
        <main>
            <HelloWorldButton />
        </main>
        <Scopes from='js' />
    ");
}

// DOES WORK! The children are compiled first before they are passed to this function
function Layout($_, $children){
    $childrenJoined = implode("", $children);
    return Phx::Run("
        <Scopes from='js' />
        <main>
            $childrenJoined
        </main>
    ")
}
function App(){
    return Phx::Run("
        <HelloWorldButton />
    ");
}

echo App();

```

## Conclusion

The Phx package provides a convenient way to write PHP code using a JSX-like syntax. It allows you to create components, manage scoped js and css, render components, and format attributes easily. By leveraging the Phx package, you can build more expressive and maintainable PHP applications.

You can also check out the 'example' folder, but all examples are almost the same as those in the documentation