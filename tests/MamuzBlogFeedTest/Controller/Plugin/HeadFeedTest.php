<?php

namespace MamuzBlogFeedTest\Controller\Plugin;

use MamuzBlogFeed\Controller\Plugin\HeadFeed;

class HeadFeedTest extends \PHPUnit_Framework_TestCase
{
    /** @var HeadFeed */
    protected $fixture;

    /** @var \Zend\View\Helper\HeadLink | \Mockery\MockInterface */
    protected $headLink;

    protected function setUp()
    {
        $this->headLink = \Mockery::mock('Zend\View\Helper\HeadLink');
        $this->fixture = new HeadFeed($this->headLink);
    }

    public function testImplementingFactoryInterface()
    {
        $this->assertInstanceOf('Zend\Mvc\Controller\Plugin\AbstractPlugin', $this->fixture);
    }

    public function testAdd()
    {
        $feed = \Mockery::mock('Zend\Feed\Writer\Feed');
        $feed->shouldReceive('getTitle')->andReturn('foo');
        $feed->shouldReceive('getType')->andReturn('rss');
        $feed->shouldReceive('getLink')->andReturn('bar');

        $this->headLink->shouldReceive('appendAlternate')->with(
            'bar',
            "application/rss+xml",
            'foo'
        );

        $this->fixture->add($feed);
    }
}
