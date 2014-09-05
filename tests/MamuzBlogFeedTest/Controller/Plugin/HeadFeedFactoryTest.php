<?php

namespace MamuzBlogFeedTest\Controller\Plugin;

use MamuzBlogFeed\Controller\Plugin\HeadFeedFactory;

class HeadFeedFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var HeadFeedFactory */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new HeadFeedFactory;
    }

    public function testImplementingFactoryInterface()
    {
        $this->assertInstanceOf('Zend\ServiceManager\FactoryInterface', $this->fixture);
    }

    public function testCreation()
    {
        $headLink = \Mockery::mock('Zend\View\Helper\HeadLink');
        $viewHelperManager = \Mockery::mock('Zend\View\HelperPluginManager');
        $viewHelperManager->shouldReceive('get')->with('HeadLink')->andReturn($headLink);

        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $sm->shouldReceive('get')->with('ViewHelperManager')->andReturn($viewHelperManager);

        $plugin = $this->fixture->createService($sm);

        $this->assertInstanceOf('Zend\Mvc\Controller\Plugin\AbstractPlugin', $plugin);
    }

    public function testCreationWithServiceLocatorAwareness()
    {
        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $sl = \Mockery::mock('Zend\ServiceManager\AbstractPluginManager');
        $sl->shouldReceive('getServiceLocator')->andReturn($sm);

        $headLink = \Mockery::mock('Zend\View\Helper\HeadLink');
        $viewHelperManager = \Mockery::mock('Zend\View\HelperPluginManager');
        $viewHelperManager->shouldReceive('get')->with('HeadLink')->andReturn($headLink);
        $sm->shouldReceive('get')->with('ViewHelperManager')->andReturn($viewHelperManager);

        $plugin = $this->fixture->createService($sl);

        $this->assertInstanceOf('Zend\Mvc\Controller\Plugin\AbstractPlugin', $plugin);
    }
}
