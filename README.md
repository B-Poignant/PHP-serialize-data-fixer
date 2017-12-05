# PHP-serialize-data-fixer

Fix corrupted serialized data

## Getting Started

First repo.

### Installing

```
composer require b-poignant/serialize-data-fixer
```

### Using

A step by step series of examples that tell you have to get a development env running

Say what the step will be

```
use UnserializeFixer\Fixer;
require_once('vendor/autoload.php');

var_dump(Fixer::run('a:3:{s:3:"foo";s:3:"bar";s:3:"int";i:8;i:9;s:4:"test";}'));
```

You can set config like this : 

```
use UnserializeFixer\Fixer;
use UnserializeFixer\Config;

require_once('vendor/autoload.php');

$config = new Config();
$config->setlogEnabled(false);
$config->setResolveMethod('complete');

Fixer::setConfig($config);

var_dump(Fixer::run('a:3:{s:3:"foo";s:3:"bar";s:3:"int";i:8;i:9;s:4:"test";}'));
```

End with an example of getting some data out of the system or using it for a little demo

## Running the tests

Explain how to run the automated tests for this system

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

You can run php-unit to run few tests on sample file

```
phpunit --configuration phpunit.xml --testsuite Sample
```

## Deployment

Add additional notes about how to deploy this on a live system

## Github

* https://github.com/B-Poignant/PHP-serialize-data-fixer

## Me

* https://www.linkedin.com/in/benjaminpoignant/
* https://stackoverflow.com/users/3224358/benjamin-poignant