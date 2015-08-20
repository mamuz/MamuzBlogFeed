<?php

namespace MamuzBlogFeedTest;

use MamuzBlogFeed\Module;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    /** @var Module */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new Module;
    }

    public function testImplementingFeatures()
    {
        $this->assertInstanceOf('Zend\ModuleManager\Feature\BootstrapListenerInterface', $this->fixture);
        $this->assertInstanceOf('Zend\ModuleManager\Feature\ConfigProviderInterface', $this->fixture);
        $this->assertInstanceOf('Zend\ModuleManager\Feature\InitProviderInterface', $this->fixture);
    }

    public function testConfigRetrieval()
    {
        $this->assertNotEmpty($this->fixture->getConfig());
    }

    public function testLoadingModules()
    {
        $moduleManager = \Mockery::mock('Zend\ModuleManager\ModuleManagerInterface');
        $moduleManager->shouldReceive('loadModule')->with('MamuzBlog');

        $this->fixture->init($moduleManager);
    }
}
