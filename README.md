# Nano http framework

[![Coverage Status](https://coveralls.io/repos/github/awesomite/nano/badge.svg?branch=master)](https://coveralls.io/github/awesomite/nano?branch=master)
[![Build Status](https://travis-ci.org/awesomite/nano.svg?branch=master)](https://travis-ci.org/awesomite/nano)

```php
<?php

use Awesomite\Chariot\RouterInterface;
use Awesomite\Nano\Container\Container;
use Awesomite\Nano\Nano;

/*
 * Creating nano app
 */
$app = new Nano();

/**
 * Filling the container
 */
$app->getContainer()
    ->set('mysql', new MyMysqlConnection());

/*
 * Enable error handler
 */
$app
    ->enableDebugMode()
    ->enableErrorHandling();

/*
 * Register callbacks
 */
$app->get('/', function () {
    return 'Welcome on my website';
});
$app->get('/hello-{{ name }}', function (string $name) {
    return 'Hello ' . $name;
});
$app->get('/category-{{ category }}/item-{{ itemId :int }}', function (int $itemId, string $category) {
    return sprintf('Item %d from category %s', $itemId, $category);
});
$app->get('/mysql', function (MyMysqlConnection $mysql) { // $mysql comes from container
    $mysql->execute('MY QUERY...');
    return 'OK';
});

/*
 * Named routes
 */
$app->get(['/user-{{ name }}', 'userpage'], function (string $name) {
    return 'Hello, ' . $name . '!';
});
$app->get('/menu', function (RouterInterface $router) {
    return [
        $router->linkTo('userpage')->withParam('name', 'John'),
        $router->linkTo('userpage')->withParam('name', 'Jane'),
    ];
});

/*
 * Voila!
 */
$app->run();
```
