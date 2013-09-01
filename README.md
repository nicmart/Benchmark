# Benchmark
[![Build Status](https://travis-ci.org/nicmart/Benchmark.png?branch=master)](https://travis-ci.org/nicmart/Benchmark)
[![Coverage Status](https://coveralls.io/repos/nicmart/Benchmark/badge.png?branch=master)](https://coveralls.io/r/nicmart/Benchmark?branch=master)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/nicmart/Benchmark/badges/quality-score.png?s=0d038caab3aaf002666f805ff4c1b0638f377db5)](https://scrutinizer-ci.com/g/nicmart/Benchmark/)

A framework for benchmarking php code.

In early stage of development. Stay tuned.

## Install

The best way to install Benchmark is [through composer](http://getcomposer.org).

Just create a composer.json file for your project:

```JSON
{
    "require": {
        "nicmart/benchmark": "dev-master"
    }
}
```

Then you can run these two commands to install it:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar install

or simply run `composer install` if you have have already [installed the composer globally](http://getcomposer.org/doc/00-intro.md#globally).

Then you can include the autoloader, and you will have access to the library classes:

```php
<?php
require 'vendor/autoload.php';
```