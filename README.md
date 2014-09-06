# MamuzBlogFeed

[![Build Status](https://travis-ci.org/mamuz/MamuzBlogFeed.svg?branch=master)](https://travis-ci.org/mamuz/MamuzBlogFeed)
[![Coverage Status](https://coveralls.io/repos/mamuz/MamuzBlogFeed/badge.png?branch=master)](https://coveralls.io/r/mamuz/MamuzBlogFeed?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mamuz/MamuzBlogFeed/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mamuz/MamuzBlogFeed/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/091c0080-0728-4a53-b1ca-1495c7b926b6/mini.png)](https://insight.sensiolabs.com/projects/091c0080-0728-4a53-b1ca-1495c7b926b6)
[![HHVM Status](http://hhvm.h4cc.de/badge/mamuz/mamuz-blog-feed.png)](http://hhvm.h4cc.de/package/mamuz/mamuz-blog-feed)
[![Dependency Status](https://www.versioneye.com/user/projects/540625c8c4c187d04f000069/badge.svg)](https://www.versioneye.com/user/projects/540625c8c4c187d04f000069)

[![Latest Stable Version](https://poser.pugx.org/mamuz/mamuz-blog-feed/v/stable.svg)](https://packagist.org/packages/mamuz/mamuz-blog-feed)
[![Latest Unstable Version](https://poser.pugx.org/mamuz/mamuz-blog-feed/v/unstable.svg)](https://packagist.org/packages/mamuz/mamuz-blog-feed)
[![Total Downloads](https://poser.pugx.org/mamuz/mamuz-blog-feed/downloads.svg)](https://packagist.org/packages/mamuz/mamuz-blog-feed)
[![License](https://poser.pugx.org/mamuz/mamuz-blog-feed/license.svg)](https://packagist.org/packages/mamuz/mamuz-blog-feed)

## Features

- This module provides a plugin for [`mamuz/mamuz-blog`](https://packagist.org/packages/mamuz/mamuz-blog) to create Feeds
- Atom and RSS feeds are supported
- Adds alternative link to html head automaticly
- Feeds for all Posts and for Posts found by Tag are provided
- Each feed is configurable seperatly

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

This module is usable out of the box,
but you can overwrite default configuration by adding a config file in `./config/autoload` directory.
For default configuration see
[`module.config.php`](https://github.com/mamuz/MamuzBlogFeed/blob/master/config/module.config.php)

## Workflow

If routing is successful, posts will be retrieved either with tag parameter or without tag parameter
to create a feed xml and responding to http response object.

## Events

For the sake of simplicity `Event` is used for FQN [`MamuzBlog\EventManager\Event`](https://github.com/mamuz/MamuzBlog/blob/master/src/MamuzBlog/EventManager/Event.php).

The following events are triggered by `Event::IDENTIFIER` *mamuz-blog*:

Name              | Constant                  | Description
----------------- | ------------------------- | -----------
*createFeed.pre*  | `Event::PRE_FEED_CREATE`  | Before feed creation
*createFeed.post* | `Event::POST_FEED_CREATE` | After feed creation

## Terminology

- **Feed**: Web format to publish frequently updated informations (here blog articles).
