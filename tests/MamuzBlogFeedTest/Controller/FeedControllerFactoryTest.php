<?php

namespace MamuzBlogFeedTest\Controller;

use MamuzBlogFeed\Controller\FeedControllerFactory;

class FeedControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var FeedControllerFactory */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new FeedControllerFactory;
    }

    public function testImplementingFactoryInterface()
    {
        $this->assertInstanceOf('Zend\ServiceManager\FactoryInterface', $this->fixture);
    }

    public function testCreation()
    {
        $listener = \Mockery::mock('Zend\EventManager\ListenerAggregateInterface');
        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $sm->shouldReceive('get')->with('MamuzBlog\DomainManager')->andReturn($sm);
        $sm->shouldReceive('get')->with('MamuzBlogFeed\Listener\Aggregate')->andReturn($listener);

        $controller = $this->fixture->createService($sm);

        $this->assertInstanceOf('Zend\Mvc\Controller\AbstractController', $controller);
    }

    public function testCreationWithServiceLocatorAwareness()
    {
        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $sl = \Mockery::mock('Zend\ServiceManager\AbstractPluginManager');
        $sl->shouldReceive('getServiceLocator')->andReturn($sm);

        $listener = \Mockery::mock('Zend\EventManager\ListenerAggregateInterface');
        $sm->shouldReceive('get')->with('MamuzBlog\DomainManager')->andReturn($sm);
        $sm->shouldReceive('get')->with('MamuzBlogFeed\Listener\Aggregate')->andReturn($listener);

        $controller = $this->fixture->createService($sl);

        $this->assertInstanceOf('Zend\Mvc\Controller\AbstractController', $controller);
    }
}
