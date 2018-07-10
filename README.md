[![Codacy Badge](https://api.codacy.com/project/badge/Grade/03147aa206544309a70686b9342f5b00)](https://www.codacy.com/app/needle-project/common?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=needle-project/common&amp;utm_campaign=Badge_Grade)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/dd95850e-8bed-4efe-a7fd-719f439cf570/mini.png)](https://insight.sensiolabs.com/projects/dd95850e-8bed-4efe-a7fd-719f439cf570)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/needle-project/common/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/needle-project/common/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/needle-project/common/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/needle-project/common/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/needle-project/common/badges/build.png?b=master)](https://scrutinizer-ci.com/g/needle-project/common/build-status/master)
[![Build Status](https://travis-ci.org/needle-project/common.svg?branch=master)](https://travis-ci.org/needle-project/common)

# README

## What is Common?
Common is a library packed with small re-usable utilities and helpers. 

## Requirments
For larger usage, minimum version is PHP 5.5

## Instalation
```
composer require "needle-project/common"
```
## Content
1. WordType convertor - Converts string to `camelCase`, `PascalCase` or `snake_case`
2. ArrayHelper - searches if an array has a key in depth and retrieves the value
3. Error to Exception - convert php errors to Exceptions
4. ClassFinder - Searches for classes of a certain type.


### 1. WordType convertor
Converts a set of strings of typographical conventions between them.
It handles `camelCase`, `PascalCase` and `snake_case`.
**Usage**
```
<?php
require_once 'vendor/autoload.php';

use NeedleProject\Common\Convertor\CompoundWordConvertor;

echo CompoundWordConvertor::convertToPascalCase("Hello World") . "\n";
// HelloWorld

echo CompoundWordConvertor::convertToCamelCase("Hello World") . "\n";
// helloWorld

echo CompoundWordConvertor::convertToSnakeCase("Hello World")  . "\n";
// hello_world
```
**Known issues**
Converting an already converted type will fail. Example:
```
<?php
require_once 'vendor/autoload.php';

use NeedleProject\Common\Convertor\CompoundWordConvertor;

echo CompoundWordConvertor::convertToCamelCase("fooBar");
// will output "foobar", not fooBar

echo CompoundWordConvertor::convertToPascalCase("FooBar");
// will output "Foobar", not FooBar

echo CompoundWordConvertor::convertToSnakeCase("FOO BAR");
// will output "f_o_o_b_a_r", not "foo_bar"
```
### 2. ArrayHelper
Searches for a key in depth and retrieves it or state it's presense.
**Usage**
```
<?php
require_once 'vendor/autoload.php';

use NeedleProject\Common\Helper\ArrayHelper;

$searchFor = ['level1', 'level2', 'level3'];
$searchIn = [
    'level1' => [
        'level2' => [
            'level3' => 'A value'
        ]
    ]
];

$helper = new ArrayHelper();
if ($helper->hasKeysInDepth($searchIn, $searchFor)) {
    echo $helper->getValueFromDepth($searchIn, $searchFor);
    // A value
}
```
### 3. Error to Exception
Converts a PHP error to an Exception.
Error level constans are the PHP's default ones that can be found [here](http://php.net/manual/ro/function.error-reporting.php "here").
```
<?php
require_once 'vendor/autoload.php';

use NeedleProject\Common\Util\ErrorToExceptionConverter;

class CustomException extends \Exception {
}

$convertor = new ErrorToExceptionConverter();
$convertor->convertErrorsToExceptions(E_ALL, CustomException::class);

try {
    print(a);
} catch (\Exception $e) {
    echo get_class($e) . "\n";
    echo $e->getMessage();
}

// restore the previous state of error handling
$convertor->restoreErrorHandler();
```
### 4. ClassFinder
Searches for a class of a certain sub-type.
```
<?php
require_once 'vendor/autoload.php';

use NeedleProject\Common\ClassFinder;

$classFinder = new ClassFinder(
    __DIR__ . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'fixtures',
    \Fixture\BaseInterface::class
);
$foundClasses = $classFinder->findClasses();

print_r($foundClasses);
php test.php
// Array
// (
//    [0] => Fixture\Path\ClassList\BazClass
//    [1] => Fixture\Path\ClassList\GodClass
//    [2] => Fixture\Path\FooClass
// )
```
## Contribute
Feel free to contribute:
- State ideeas
- Open pull request with improvements/bug-fixes