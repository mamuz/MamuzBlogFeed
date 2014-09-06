<?php

namespace MamuzBlogFeed\EventManager;

interface Event
{
    const PRE_FEED_CREATE = 'createFeed.pre';

    const POST_FEED_CREATE = 'createFeed.post';
}
