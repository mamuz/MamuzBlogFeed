<?php

namespace MamuzBlogFeedTest\Controller\Plugin;

use MamuzBlogFeed\Controller\Plugin\FeedFactory;

class FeedFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var FeedFactory */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new FeedFactory;
    }

    public function testImplementingFactoryInterface()
    {
        $this->assertInstanceOf('Zend\ServiceManager\FactoryInterface', $this->fixture);
    }

    public function testCreation()
    {
        $queryInterface = \Mockery::mock('MamuzBlog\Feature\PostQueryInterface');
        $configProvider = \Mockery::mock('MamuzBlogFeed\Options\ConfigProviderInterface');
        $feedFactory = \Mockery::mock('MamuzBlogFeed\Feed\Writer\BuilderInterface');

        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $sm->shouldReceive('get')->with('MamuzBlogFeed\Feed\WriterFactory')->andReturn($feedFactory);
        $sm->shouldReceive('get')->with('MamuzBlogFeed\Options\ConfigProvider')->andReturn($configProvider);
        $sm->shouldReceive('get')->with('MamuzBlog\DomainManager')->andReturn($sm);
        $sm->shouldReceive('get')->with('MamuzBlog\Service\PostQuery')->andReturn($queryInterface);

        $plugin = $this->fixture->createService($sm);

        $this->assertInstanceOf('Zend\Mvc\Controller\Plugin\AbstractPlugin', $plugin);
    }

    public function testCreationWithServiceLocatorAwareness()
    {
        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $sl = \Mockery::mock('Zend\ServiceManager\AbstractPluginManager');
        $sl->shouldReceive('getServiceLocator')->andReturn($sm);

        $queryInterface = \Mockery::mock('MamuzBlog\Feature\PostQueryInterface');
        $configProvider = \Mockery::mock('MamuzBlogFeed\Options\ConfigProviderInterface');
        $feedFactory = \Mockery::mock('MamuzBlogFeed\Feed\Writer\BuilderInterface');

        $sm->shouldReceive('get')->with('MamuzBlogFeed\Feed\WriterFactory')->andReturn($feedFactory);
        $sm->shouldReceive('get')->with('MamuzBlogFeed\Options\ConfigProvider')->andReturn($configProvider);
        $sm->shouldReceive('get')->with('MamuzBlog\DomainManager')->andReturn($sm);
        $sm->shouldReceive('get')->with('MamuzBlog\Service\PostQuery')->andReturn($queryInterface);

        $plugin = $this->fixture->createService($sl);

        $this->assertInstanceOf('Zend\Mvc\Controller\Plugin\AbstractPlugin', $plugin);
    }
}
