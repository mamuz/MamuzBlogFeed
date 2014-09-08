<?php

namespace MamuzBlogFeedTest\Options;

use MamuzBlogFeed\Options\ConfigProvider;

class ConfigProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ConfigProvider */
    protected $fixture;

    /** @var \MamuzBlogFeed\Filter\AbstractTagParamAware | \Mockery\MockInterface */
    protected $filter;

    /** @var array */
    protected $config = array('foo' => array(12, 13), 'default' => array(1, 3));

    protected function setUp()
    {
        $this->filter = \Mockery::mock('MamuzBlogFeed\Filter\AbstractTagParamAware');
        $this->filter->shouldReceive('filter')->andReturnUsing(
            function ($value) {
                return $value;
            }
        );

        $this->fixture = new ConfigProvider($this->config, $this->filter);
    }

    public function testImplementingConfigProviderInterface()
    {
        $this->assertInstanceOf('MamuzBlogFeed\Options\ConfigProviderInterface', $this->fixture);
    }

    public function testGettingDefaultByNull()
    {
        $this->filter->shouldReceive('setTagParam')->with(null)->andReturnSelf();
        $this->assertSame($this->config['default'], $this->fixture->getFor());
    }

    public function testGettingDefault()
    {
        $this->filter->shouldReceive('setTagParam')->with('baz')->andReturnSelf();
        $this->assertSame($this->config['default'], $this->fixture->getFor('baz'));
    }

    public function testGettingUserDefault()
    {
        $this->fixture = new ConfigProvider($this->config, $this->filter, 'foo');
        $this->filter->shouldReceive('setTagParam')->with('baz')->andReturnSelf();
        $this->assertSame($this->config['foo'], $this->fixture->getFor('baz'));
    }

    public function testGetting()
    {
        $this->filter->shouldReceive('setTagParam')->with('foo')->andReturnSelf();
        $this->assertSame($this->config['foo'], $this->fixture->getFor('foo'));
    }

    public function testGettingEmpty()
    {
        $this->fixture = new ConfigProvider($this->config, $this->filter, 'baz');
        $this->assertSame(array(), $this->fixture->getFor('bar'));
    }
}
