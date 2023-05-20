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
            <h1>Click "Confirm"</h1>
            <ConfirmButton onclick='someClickHandler()' />
        </div>
    ");
}

echo App();
```

## Conclusion

The Phx package provides a convenient way to write PHP code using a JSX-like syntax. It allows you to create components, render them, and format attributes easily. By leveraging the Phx package, you can build more expressive and maintainable PHP applications.

You can also check out the 'example' folder, but all examples are almost the same as those in the documentation