<?php

namespace MamuzBlogFeedTest\Filter;

use MamuzBlogFeed\Filter\Query;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Query */
    protected $fixture;

    /** @var \Doctrine\ORM\Query | \Mockery\MockInterface */
    protected $query;

    /** @var \MamuzBlogFeed\Options\ConfigProviderInterface |\Mockery\MockInterface */
    protected $configProvider;

    /** @var string */
    protected $tag = 'foo';

    protected function setUp()
    {
        $this->query = \Mockery::mock('Doctrine\ORM\AbstractQuery');
        $this->query->shouldReceive('setFirstResult')->with(1)->andReturnSelf();
        $this->query->shouldReceive('getParameter')->with('tag')->andReturnNull($this->tag);
        $this->configProvider = \Mockery::mock('MamuzBlogFeed\Options\ConfigProviderInterface');
        $this->fixture = new Query($this->configProvider);
    }

    public function testImplementingFilterInterface()
    {
        $this->assertInstanceOf('Zend\Filter\FilterInterface', $this->fixture);
    }

    public function testFilteringWrongValueType()
    {
        $value = 123;
        $this->assertSame($value, $this->fixture->filter($value));
    }

    public function testFilteringWithDefaultMaxResults()
    {
        $this->configProvider->shouldReceive('getFor')->with($this->tag)->andReturn(array());
        $this->query->shouldReceive('setMaxResults')->with(100);

        $this->assertSame($this->query, $this->fixture->filter($this->query));
    }

    public function testFilteringWithUserDefaultMaxResults()
    {
        $this->fixture = new Query($this->configProvider, 89);
        $this->configProvider->shouldReceive('getFor')->with($this->tag)->andReturn(array());
        $this->query->shouldReceive('setMaxResults')->with(89);

        $this->assertSame($this->query, $this->fixture->filter($this->query));
    }

    public function testFilteringWithMaxResultsFromConfig()
    {
        $this->configProvider->shouldReceive('getFor')->with($this->tag)->andReturn(array('maxResults' => 400));
        $this->query->shouldReceive('setMaxResults')->with(400);

        $this->assertSame($this->query, $this->fixture->filter($this->query));
    }
}
