<?php

namespace MamuzBlogFeedTest\Extractor;

use MamuzBlogFeed\Extractor\FeedEntry;

class FeedEntryTest extends \PHPUnit_Framework_TestCase
{
    /** @var FeedEntry */
    protected $fixture;

    /** @var \Zend\View\Renderer\RendererInterface | \Mockery\MockInterface */
    protected $renderer;

    /** @var \MamuzBlog\Entity\Post | \Mockery\MockInterface */
    protected $post;

    /** @var array */
    protected $exp = array(
        'id'           => 'hashed',
        'link'         => 'href',
        'title'        => 'foo',
        'description'  => 'bar',
        'content'      => 'baz',
        'dateModified' => '2012-12-12',
        'dateCreated'  => '2011-12-12',
    );

    protected function setUp()
    {
        $this->post = \Mockery::mock('MamuzBlog\Entity\Post');
        $this->renderer = \Mockery::mock('Zend\View\Renderer\RendererInterface');
        $this->fixture = new FeedEntry($this->renderer);
    }

    protected function prepareMocks()
    {
        $this->post->shouldReceive('getId')->once()->andReturn($this->exp['id']);
        $this->post->shouldReceive('getTitle')->once()->andReturn($this->exp['title']);
        $this->post->shouldReceive('getDescription')->once()->andReturn($this->exp['description']);
        $this->post->shouldReceive('getContent')->once()->andReturn($this->exp['content']);
        $this->post->shouldReceive('getModifiedAt')->once()->andReturn($this->exp['dateModified']);
        $this->post->shouldReceive('getCreatedAt')->once()->andReturn($this->exp['dateCreated']);

        $this->renderer->shouldReceive('hashId')->once()->with($this->exp['id'])->andReturn($this->exp['id']);
        $this->renderer->shouldReceive('permaLinkPost')->once()->with($this->post)->andReturn($this->exp['link']);
        $this->renderer->shouldReceive('markdown')->once()
            ->with($this->exp['description'])->andReturn($this->exp['description']);
        $this->renderer->shouldReceive('markdown')->once()
            ->with($this->exp['content'])->andReturn($this->exp['content']);
    }

    public function testImplementingExtractionInterface()
    {
        $this->assertInstanceOf('Zend\Stdlib\Extractor\ExtractionInterface', $this->fixture);
    }

    public function testExtractionWithWrongValue()
    {
        $this->assertSame(array(), $this->fixture->extract(1));
    }

    public function testExtractionWithoutTags()
    {
        $this->prepareMocks();
        $this->post->shouldReceive('getTags')->once()->andReturn(array());
        $this->assertSame($this->exp, $this->fixture->extract($this->post));
    }

    public function testExtractionWithTags()
    {
        $this->exp['categories'] = array(array('term' => 'foo', 'scheme' => 'bar'));

        $tag = \Mockery::mock('MamuzBlog\Entity\Tag');
        $tag->shouldReceive('getName')->andReturn('foo');
        $this->renderer->shouldReceive('permaLinkTag')->with('foo')->andReturn('bar');

        $this->prepareMocks();
        $this->post->shouldReceive('getTags')->once()->andReturn(array($tag));
        $this->assertSame($this->exp, $this->fixture->extract($this->post));
    }
}
