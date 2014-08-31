# MamuzBlogFeed

## Features

- This module provides a plugin for [`mamuz/mamuz-blog`](https://packagist.org/packages/mamuz/mamuz-blog) to create Feeds
- tba

## Installation

The recommended way to install
[`mamuz/mamuz-blog-feed`](https://packagist.org/packages/mamuz/mamuz-blog-feed) is through
[composer](http://getcomposer.org/) by adding dependency to your `composer.json`:

```json
{
    "require": {
        "mamuz/mamuz-blog-feed": "*"
    }
}
```

After that run `composer update` and enable this module for ZF2 by adding
`MamuzBlogFeed` to `modules` in `./config/application.config.php`:

```php
// ...
    'modules' => array(
        'MamuzBlogFeed',
    ),
```

## Configuration

tba

### Default configuration

This module is usable out of the box,
but you can overwrite default configuration by adding a config file in `./config/autoload` directory.
For default configuration see
[`module.config.php`](https://github.com/mamuz/MamuzBlogFeed/blob/master/config/module.config.php)

## Workflow

tba

## Terminology

- **Feed**: Web format to publish frequently updated informations (here blog articles).
