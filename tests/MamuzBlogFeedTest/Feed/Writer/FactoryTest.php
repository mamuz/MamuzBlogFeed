<?php

namespace MamuzBlogFeedTest\Feed\Writer;

use MamuzBlogFeed\Feed\Writer\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Factory */
    protected $fixture;

    /** @var \Zend\Stdlib\Extractor\ExtractionInterface | \Mockery\MockInterface */
    protected $extractor;

    /** @var \Zend\Stdlib\Hydrator\HydrationInterface | \Mockery\MockInterface */
    protected $hydrator;

    protected function setUp()
    {
        $this->extractor = \Mockery::mock('Zend\Stdlib\Extractor\ExtractionInterface');
        $this->hydrator = \Mockery::mock('Zend\Stdlib\Hydrator\HydrationInterface');

        $this->fixture = new Factory($this->extractor, $this->hydrator);
    }

    public function testImplementingFactoryInterface()
    {
        $this->assertInstanceOf('MamuzBlogFeed\Feed\Writer\FactoryInterface', $this->fixture);
    }

    public function testCreation()
    {
        $post = \Mockery::mock('MamuzBlog\Entity\Post');
        $options = array('foo');
        $this->extractor->shouldReceive('extract')->with($post)->andReturn($options);
        $this->hydrator->shouldReceive('hydrate')->andReturnUsing(
            function ($data, $object) use ($options) {
                if ($object instanceof \Zend\Feed\Writer\Feed) {
                    $this->assertSame('rss', $object->getType());
                } else {
                    $this->assertInstanceOf('Zend\Feed\Writer\Entry', $object);
                }
                $this->assertSame($options, $data);
                return $object;
            }
        );

        $posts = new \ArrayObject(array($post));

        $this->assertInstanceOf('Zend\Feed\Writer\Feed', $this->fixture->create($options, $posts));
    }
}
