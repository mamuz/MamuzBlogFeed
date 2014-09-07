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
        $configProvider = \Mockery::mock('MamuzBlogFeed\Options\ConfigProviderInterface');

        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $sm->shouldReceive('get')->with('MamuzBlog\DomainManager')->andReturn($sm);
        $sm->shouldReceive('get')->with('MamuzBlogFeed\Options\ConfigProvider')->andReturn($configProvider);

        $aggregate = $this->fixture->createService($sm);

        $this->assertInstanceOf('Zend\EventManager\ListenerAggregateInterface', $aggregate);
    }

    public function testCreationWithServiceLocatorAwareness()
    {
        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $sl = \Mockery::mock('Zend\ServiceManager\AbstractPluginManager');
        $sl->shouldReceive('getServiceLocator')->andReturn($sm);

        $configProvider = \Mockery::mock('MamuzBlogFeed\Options\ConfigProviderInterface');

        $sm->shouldReceive('get')->with('MamuzBlog\DomainManager')->andReturn($sm);
        $sm->shouldReceive('get')->with('MamuzBlogFeed\Options\ConfigProvider')->andReturn($configProvider);

        $aggregate = $this->fixture->createService($sl);

        $this->assertInstanceOf('Zend\EventManager\ListenerAggregateInterface', $aggregate);
    }
}
