<?php

namespace MamuzBlogFeed\EventManager;

use MamuzBlog\EventManager\Event as MamuzBlogEvent;

interface Event extends MamuzBlogEvent
{
    const PRE_FEED_CREATE = 'createFeed.pre';

    const POST_FEED_CREATE = 'createFeed.post';
}
