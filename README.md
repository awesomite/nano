# Nano http framework

```php
<?php

use Awesomite\Nano\Nano;

$app = new Nano();

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

/*
 * Voila!
 */
$app->run();
```
