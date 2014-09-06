<?php

namespace MamuzBlogFeedTest\Options;

use MamuzBlogFeed\Options\ConfigProvider;

class ConfigProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ConfigProvider */
    protected $fixture;

    /** @var array */
    protected $config = array('foo' => array(12, 13), 'default' => array(1, 3));

    protected function setUp()
    {
        $this->fixture = new ConfigProvider($this->config);
    }

    public function testImplementingConfigProviderInterface()
    {
        $this->assertInstanceOf('MamuzBlogFeed\Options\ConfigProviderInterface', $this->fixture);
    }

    public function testGettingDefault()
    {
        $this->assertSame($this->config['default'], $this->fixture->getFor());
        $this->assertSame($this->config['default'], $this->fixture->getFor('baz'));
        $this->assertSame($this->config['default'], $this->fixture->getFor(132));
        $this->assertSame($this->config['default'], $this->fixture->getFor(array()));
    }

    public function testGettingUserDefault()
    {
        $this->fixture = new ConfigProvider($this->config, 'foo');
        $this->assertSame($this->config['foo'], $this->fixture->getFor());
        $this->assertSame($this->config['foo'], $this->fixture->getFor('baz'));
        $this->assertSame($this->config['foo'], $this->fixture->getFor(132));
        $this->assertSame($this->config['foo'], $this->fixture->getFor(array()));
    }

    public function testGetting()
    {
        $this->assertSame($this->config['foo'], $this->fixture->getFor('foo'));
    }

    public function testGettingEmpty()
    {
        $this->fixture = new ConfigProvider($this->config, 'baz');
        $this->assertSame(array(), $this->fixture->getFor('bar'));
    }
}
