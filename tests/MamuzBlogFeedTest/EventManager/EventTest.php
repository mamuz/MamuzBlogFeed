<?php

namespace MamuzBlogFeedTest\EventManager;

use MamuzBlogFeed\EventManager\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testEventIdentifier()
    {
        $this->assertSame('mamuz-blog', Event::IDENTIFIER);
    }

    public function testEventNames()
    {
        $this->assertSame('createFeed.pre', Event::PRE_FEED_CREATE);
        $this->assertSame('createFeed.post', Event::POST_FEED_CREATE);
    }
}
