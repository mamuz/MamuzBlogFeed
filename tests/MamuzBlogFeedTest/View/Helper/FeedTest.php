<?php

namespace MamuzBlogFeedTest\View\Helper;

use MamuzBlogFeed\View\Helper\Feed;

class FeedTest extends \PHPUnit_Framework_TestCase
{
    /** @var Feed */
    protected $fixture;

    /** @var \Zend\Feed\Writer\Feed | \Mockery\MockInterface */
    protected $feedWriter;

    /** @var \Zend\Feed\Writer\Entry | \Mockery\MockInterface */
    protected $entry;

    /** @var \MamuzBlog\Entity\Post | \Mockery\MockInterface */
    protected $post;

    /** @var \Zend\View\Renderer\RendererInterface | \Mockery\MockInterface */
    protected $renderer;

    protected function setUp()
    {
        $this->feedWriter = \Mockery::mock('Zend\Feed\Writer\Feed');
        $this->entry = \Mockery::mock('Zend\Feed\Writer\Entry');
        $this->post = \Mockery::mock('MamuzBlog\Entity\Post');
        $this->renderer = \Mockery::mock('Zend\View\Renderer\RendererInterface');

        $this->fixture = new Feed(
            $this->feedWriter,
            $this->entry,
            new \ArrayObject(array($this->post)),
            $this->renderer
        );
    }

    public function testImplementingHelperInterface()
    {
        $this->assertInstanceOf('Zend\View\Helper\HelperInterface', $this->fixture);
    }

    public function testRendering()
    {
        // @todo
        return;
        $this->assertSame($this->feedWriter, $this->fixture->render());
    }
}
