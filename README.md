Gephart Event Manager
===

[![php](https://github.com/gephart/event-manager/actions/workflows/php.yml/badge.svg?branch=master)](https://github.com/gephart/event-manager/actions)

Dependencies
---
 - PHP >= 7.1

Instalation
---

```
composer require gephart/event-manager
```

Using
---

Basic using:

```php
$listener1 = function () { echo "Hello"; };
$listener2 = function () { echo "World"; };

$event_manager->attach("my.event", $listener1, 200);
$event_manager->attach("my.event", $listener2, 100);

$event_manager->trigger("my.event"); // HelloWorld
```