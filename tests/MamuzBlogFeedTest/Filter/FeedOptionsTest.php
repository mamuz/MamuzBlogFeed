<?php

namespace MamuzBlogFeedTest\Filter;

use MamuzBlogFeed\Filter\FeedOptions;

class FeedOptionsTest extends \PHPUnit_Framework_TestCase
{
    /** @var FeedOptions */
    protected $fixture;

    /** @var \Zend\View\Renderer\RendererInterface | \Mockery\MockInterface */
    protected $renderer;

    protected function setUp()
    {
        $this->renderer = \Mockery::mock('Zend\View\Renderer\RendererInterface');
        $this->fixture = new FeedOptions($this->renderer);
    }

    public function testImplementingFilterInterface()
    {
        $this->assertInstanceOf('Zend\Filter\FilterInterface', $this->fixture);
    }

    public function testMutateAndAccessTagParam()
    {
        $this->assertNull($this->fixture->getTagParam());
        $this->fixture->setTagParam('foo');
        $this->assertSame('foo', $this->fixture->getTagParam());
        $return = $this->fixture->setTagParam(null);
        $this->assertNull($this->fixture->getTagParam());
        $this->assertSame($this->fixture, $return);
    }

    public function testFilteringExistingValues()
    {
        $expected = array(
            'feedUrl' => 1,
            'baseUrl' => 2,
            'link'    => 3,
        );

        $this->assertSame($expected, $this->fixture->filter($expected));
    }

    public function testFiltering()
    {
        $tag = 'foo';
        $this->fixture->setTagParam($tag);

        $this->renderer->shouldReceive('permaLinkTag')->with($tag)->andReturn('link');
        $this->renderer->shouldReceive('serverUrl')->andReturn('server_');
        $this->renderer->shouldReceive('url')
            ->with('blogFeedPosts', array('tag' => $tag))->andReturn('feed');

        $result = $this->fixture->filter(123);

        $this->assertSame('server_feed', $result['feedUrl']);
        $this->assertSame('link', $result['link']);
        $this->assertSame('server_', $result['baseUrl']);
    }
}
