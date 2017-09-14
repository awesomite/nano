# Nano http framework

[![Coverage Status](https://coveralls.io/repos/github/awesomite/nano/badge.svg?branch=master)](https://coveralls.io/github/awesomite/nano?branch=master)
[![Build Status](https://travis-ci.org/awesomite/nano.svg?branch=master)](https://travis-ci.org/awesomite/nano)

```php
<?php

use Awesomite\Nano\Nano;

$app = new Nano();

$app->getContainer()->set('mysql', new MyMysqlConnection());

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
$app->get('showItem', '/category-{{ categoryName }}/item-{{ itemId :int }}', function (int $itemId, string $category) {
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
