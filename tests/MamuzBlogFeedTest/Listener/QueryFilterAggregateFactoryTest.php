<?php

namespace MamuzBlogFeedTest\Listener;

use MamuzBlogFeed\Listener\QueryFilterAggregateFactory;

class QueryFilterAggregateFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var QueryFilterAggregateFactory */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new QueryFilterAggregateFactory;
    }

    public function testImplementingFactoryInterface()
    {
        $this->assertInstanceOf('Zend\ServiceManager\FactoryInterface', $this->fixture);
    }

    public function testCreation()
    {
        $config = array('MamuzBlogFeed' => array());

        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $sm->shouldReceive('get')->with('Config')->andReturn($config);

        $aggregate = $this->fixture->createService($sm);

        $this->assertInstanceOf('Zend\EventManager\ListenerAggregateInterface', $aggregate);
    }

    public function testCreationWithServiceLocatorAwareness()
    {
        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $sl = \Mockery::mock('Zend\ServiceManager\AbstractPluginManager');
        $sl->shouldReceive('getServiceLocator')->andReturn($sm);

        $config = array('MamuzBlogFeed' => array());

        $sm->shouldReceive('get')->with('Config')->andReturn($config);

        $aggregate = $this->fixture->createService($sl);

        $this->assertInstanceOf('Zend\EventManager\ListenerAggregateInterface', $aggregate);
    }
}
