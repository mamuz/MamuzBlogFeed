<?php

namespace MamuzBlogFeedTest\Filter;

use MamuzBlogFeed\Filter\Query;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Query */
    protected $fixture;

    /** @var \Doctrine\ORM\Query | \Mockery\MockInterface */
    protected $query;

    protected function setUp()
    {
        $this->query = \Mockery::mock('Doctrine\ORM\Query');
        $this->query->shouldReceive('setFirstResult')->with(1)->andReturnSelf();
        $this->fixture = new Query();
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

    public function testFilteringWithoutTagParameter()
    {
        $this->query->shouldReceive('getParameter')->with('tag')->andReturnNull();
        $this->query->shouldReceive('setMaxResults')->with(100);

        $this->assertSame($this->query, $this->fixture->filter($this->query));
    }

    public function testFilteringWithoutTagParameterAndDefaultConfig()
    {
        $this->fixture = new Query(array('default' => array('maxResults' => 200)));
        $this->query->shouldReceive('getParameter')->with('tag')->andReturnNull();
        $this->query->shouldReceive('setMaxResults')->with(200);

        $this->assertSame($this->query, $this->fixture->filter($this->query));
    }

    public function testFilteringWithTagParameterAndConfig()
    {
        $this->fixture = new Query(array('foo' => array('maxResults' => 200)));
        $this->query->shouldReceive('getParameter')->with('tag')->andReturn('foo');
        $this->query->shouldReceive('setMaxResults')->with(200);

        $this->assertSame($this->query, $this->fixture->filter($this->query));
    }

    public function testFilteringWithTagParameterAndWithoutConfig()
    {
        $this->query->shouldReceive('getParameter')->with('tag')->andReturn('foo');
        $this->query->shouldReceive('setMaxResults')->with(100);

        $this->assertSame($this->query, $this->fixture->filter($this->query));
    }
}
