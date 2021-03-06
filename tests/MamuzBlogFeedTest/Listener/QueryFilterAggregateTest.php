<?php

namespace MamuzBlogFeedTest\Listener;

use MamuzBlog\EventManager\Event;
use MamuzBlogFeed\Listener\QueryFilterAggregate;

class QueryFilterAggregateTest extends \PHPUnit_Framework_TestCase
{
    /** @var QueryFilterAggregate */
    protected $fixture;

    /** @var \Zend\Filter\FilterInterface | \Mockery\MockInterface */
    protected $filter;

    protected function setUp()
    {
        $this->filter = \Mockery::mock('MamuzBlogFeed\Filter\AbstractTagParamAware');
        $this->fixture = new QueryFilterAggregate($this->filter);
    }

    public function testExtendingAbstractListenerAggregate()
    {
        $this->assertInstanceOf('Zend\EventManager\AbstractListenerAggregate', $this->fixture);
    }

    public function testAttaching()
    {
        $sharedEventManager = \Mockery::mock('Zend\EventManager\SharedEventManagerInterface');
        $sharedEventManager->shouldReceive('attach')->once()->with(
            Event::IDENTIFIER,
            Event::PRE_PAGINATION_CREATE,
            array($this->fixture, 'onPaginationCreate')
        );
        $events = \Mockery::mock('Zend\EventManager\EventManagerInterface');
        $events->shouldReceive('getSharedManager')->once()->andReturn($sharedEventManager);

        $this->fixture->attach($events);
    }

    public function testListeningOnPaginationCreate()
    {
        return;
        $event = \Mockery::mock('Zend\EventManager\EventInterface');
        $event->shouldReceive('getParam')->once()->with('query')->andReturn('query');
        $this->filter->shouldReceive('setTagParam')->with(null)->andReturnSelf();
        $this->filter->shouldReceive('filter')->once()->with('query');

        $this->fixture->onPaginationCreate($event);
    }
}
