<?php

namespace MamuzBlogFeedTest\Feed;

use MamuzBlogFeed\Feed\WriterFactory;

class WriterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var WriterFactory */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new WriterFactory;
    }

    public function testImplementingFactoryInterface()
    {
        $this->assertInstanceOf('Zend\ServiceManager\FactoryInterface', $this->fixture);
    }

    public function testCreation()
    {
        $renderer = \Mockery::mock('Zend\View\Renderer\RendererInterface');
        $viewHelperManager = \Mockery::mock('Zend\View\HelperPluginManager');
        $viewHelperManager->shouldReceive('getRenderer')->andReturn($renderer);

        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $sm->shouldReceive('get')->with('ViewHelperManager')->andReturn($viewHelperManager);

        $factory = $this->fixture->createService($sm);

        $this->assertInstanceOf('MamuzBlogFeed\Feed\Writer\BuilderInterface', $factory);
    }

    public function testCreationWithServiceLocatorAwareness()
    {
        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $sl = \Mockery::mock('Zend\ServiceManager\AbstractPluginManager');
        $sl->shouldReceive('getServiceLocator')->andReturn($sm);

        $renderer = \Mockery::mock('Zend\View\Renderer\RendererInterface');
        $viewHelperManager = \Mockery::mock('Zend\View\HelperPluginManager');
        $viewHelperManager->shouldReceive('getRenderer')->andReturn($renderer);

        $sm->shouldReceive('get')->with('ViewHelperManager')->andReturn($viewHelperManager);

        $factory = $this->fixture->createService($sl);

        $this->assertInstanceOf('MamuzBlogFeed\Feed\Writer\BuilderInterface', $factory);
    }
}
