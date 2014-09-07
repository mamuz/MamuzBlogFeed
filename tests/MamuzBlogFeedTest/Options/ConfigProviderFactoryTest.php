<?php

namespace MamuzBlogFeedTest\Options;

use MamuzBlogFeed\Options\ConfigProviderFactory;

class ConfigProviderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ConfigProviderFactory */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new ConfigProviderFactory;
    }

    public function testImplementingFactoryInterface()
    {
        $this->assertInstanceOf('Zend\ServiceManager\FactoryInterface', $this->fixture);
    }

    public function testCreation()
    {
        $config = array('MamuzBlogFeed' => array());
        $renderer = \Mockery::mock('Zend\View\Renderer\RendererInterface');
        $viewHelperManager = \Mockery::mock('Zend\View\HelperPluginManager');
        $viewHelperManager->shouldReceive('getRenderer')->andReturn($renderer);
        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $sm->shouldReceive('get')->with('ViewHelperManager')->andReturn($viewHelperManager);
        $sm->shouldReceive('get')->with('Config')->andReturn($config);

        $options = $this->fixture->createService($sm);

        $this->assertInstanceOf('MamuzBlogFeed\Options\ConfigProviderInterface', $options);
    }

    public function testCreationWithServiceLocatorAwareness()
    {
        $sm = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $sl = \Mockery::mock('Zend\ServiceManager\AbstractPluginManager');
        $sl->shouldReceive('getServiceLocator')->andReturn($sm);

        $config = array('MamuzBlogFeed' => array());
        $renderer = \Mockery::mock('Zend\View\Renderer\RendererInterface');
        $viewHelperManager = \Mockery::mock('Zend\View\HelperPluginManager');
        $viewHelperManager->shouldReceive('getRenderer')->andReturn($renderer);
        $sm->shouldReceive('get')->with('ViewHelperManager')->andReturn($viewHelperManager);
        $sm->shouldReceive('get')->with('Config')->andReturn($config);

        $options = $this->fixture->createService($sl);

        $this->assertInstanceOf('MamuzBlogFeed\Options\ConfigProviderInterface', $options);
    }
}
