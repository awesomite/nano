# Nano http framework

[![Coverage Status](https://coveralls.io/repos/github/awesomite/nano/badge.svg?branch=master)](https://coveralls.io/github/awesomite/nano?branch=master)
[![Build Status](https://travis-ci.org/awesomite/nano.svg?branch=master)](https://travis-ci.org/awesomite/nano)

```php
<?php

use Awesomite\Nano\Container\Container;
use Awesomite\Nano\Nano;

/*
 * Prepare container
 * Container MUST implement Psr\Container\ContainerInterface
 * 
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md
 */
$container = new Container();
$container->set('mysql', new MyMysqlConnection());

/*
 * Creating nano app
 * Both arguments are optional
 */
$app = new Nano(null, $container);

/*
 * Enable error handler
 */
$app
    ->enableDebugMode()
    ->enableErrorHandling();

/*
 * Register callbacks
 */
$app->get('home', '/', function () {
    return 'Welcome on my website';
});
$app->get('greetings', '/hello-{{ name }}', function (string $name) {
    return 'Hello ' . $name;
});
$app->get('showItem', '/category-{{ category }}/item-{{ itemId :int }}', function (int $itemId, string $category) {
    return sprintf('Item %d from category %s', $itemId, $category);
});
$app->get('mysqlPing', '/mysql', function (MyMysqlConnection $mysql) { // $mysql comes from container
    $mysql->execute('MY QUERY...');
    return 'OK';
});

/*
 * Voila!
 */
$app->run();
```
