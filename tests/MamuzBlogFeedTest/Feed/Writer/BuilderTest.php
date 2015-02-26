<?php

namespace MamuzBlogFeedTest\Feed\Writer;

use MamuzBlogFeed\Feed\Writer\Builder;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Builder */
    protected $fixture;

    /** @var \Zend\Stdlib\Extractor\ExtractionInterface | \Mockery\MockInterface */
    protected $extractor;

    /** @var \Zend\Stdlib\Hydrator\HydrationInterface | \Mockery\MockInterface */
    protected $hydrator;

    /** @var int */
    protected $iteratorCnt = 0;

    protected function setUp()
    {
        $this->extractor = \Mockery::mock('Zend\Stdlib\Extractor\ExtractionInterface');
        $this->hydrator = \Mockery::mock('Zend\Stdlib\Hydrator\HydrationInterface');

        $this->fixture = new Builder($this->extractor, $this->hydrator);
    }

    public function testImplementingFactoryInterface()
    {
        $this->assertInstanceOf('MamuzBlogFeed\Feed\Writer\BuilderInterface', $this->fixture);
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

    public function testCreationWithUserFeed()
    {
        $entry = \Mockery::mock('Zend\Feed\Writer\Entry');
        $entry->shouldReceive('setCopyright')->with('foo');
        $entry->shouldReceive('addAuthors')->with(array(1, 2));
        $entry->shouldReceive('getDateModified')->andReturn('2012-12-12');

        $feed = \Mockery::mock('Zend\Feed\Writer\Feed');
        $feed->shouldReceive('getCopyright')->andReturn('foo');
        $feed->shouldReceive('getAuthors')->andReturn(array(1, 2));
        $feed->shouldReceive('setType')->with('rss');
        $feed->shouldReceive('setDateModified');
        $feed->shouldReceive('getDateModified')->andReturnNull();
        $feed->shouldReceive('setLastBuildDate');
        $feed->shouldReceive('setGenerator');
        $feed->shouldReceive('addEntry');
        $feed->shouldReceive('createEntry')->andReturn($entry);

        $this->fixture = new Builder($this->extractor, $this->hydrator, $feed);

        $post = \Mockery::mock('MamuzBlog\Entity\Post');
        $options = array('foo');
        $this->extractor->shouldReceive('extract')->with($post)->andReturn($options);
        $this->hydrator->shouldReceive('hydrate')->andReturnUsing(
            function ($data, $object) use ($options, $feed) {
                if ($object instanceof \Zend\Feed\Writer\Feed) {
                    $this->assertSame($feed, $object);
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

    public function testCreationWithUserFeedAndEntryDatetimeObject()
    {
        $entry = \Mockery::mock('Zend\Feed\Writer\Entry');
        $entry->shouldReceive('setCopyright')->with('foo');
        $entry->shouldReceive('addAuthors')->with(array(1, 2));
        $entry->shouldReceive('getDateModified')->andReturn(new \DateTime);

        $feed = \Mockery::mock('Zend\Feed\Writer\Feed');
        $feed->shouldReceive('getCopyright')->andReturn('foo');
        $feed->shouldReceive('getAuthors')->andReturn(array(1, 2));
        $feed->shouldReceive('setType')->with('rss');
        $feed->shouldReceive('getDateModified')->andReturn(new \DateTime);
        $feed->shouldReceive('setLastBuildDate');
        $feed->shouldReceive('setGenerator');
        $feed->shouldReceive('addEntry');
        $feed->shouldReceive('createEntry')->andReturn($entry);

        $this->fixture = new Builder($this->extractor, $this->hydrator, $feed);

        $post = \Mockery::mock('MamuzBlog\Entity\Post');
        $options = array('foo');
        $this->extractor->shouldReceive('extract')->with($post)->andReturn($options);
        $this->hydrator->shouldReceive('hydrate')->andReturnUsing(
            function ($data, $object) use ($options, $feed) {
                if ($object instanceof \Zend\Feed\Writer\Feed) {
                    $this->assertSame($feed, $object);
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

    public function testCreationWithUserFeedAndEntryDatetimeObjectOrdered()
    {
        $lastDate = new \DateTime('2012-12-12');
        $firstDate = new \DateTime('2011-12-12');
        $entry = \Mockery::mock('Zend\Feed\Writer\Entry');
        $entry->shouldReceive('setCopyright')->with('foo');
        $entry->shouldReceive('addAuthors')->with(array(1, 2));

        $entryFirst = clone $entry;
        $entryLast = clone $entry;
        $entryFirst->shouldReceive('getDateModified')->andReturn($firstDate);
        $entryLast->shouldReceive('getDateModified')->andReturn($lastDate);

        $feed = \Mockery::mock('Zend\Feed\Writer\Feed');
        $feed->shouldReceive('getCopyright')->andReturn('foo');
        $feed->shouldReceive('getAuthors')->andReturn(array(1, 2));
        $feed->shouldReceive('setType')->with('rss');
        $feed->shouldReceive('setDateModified')->with($lastDate);
        $feed->shouldReceive('getDateModified')->andReturnNull();
        $feed->shouldReceive('setLastBuildDate');
        $feed->shouldReceive('setGenerator');
        $feed->shouldReceive('addEntry');
        $feed->shouldReceive('createEntry')->andReturn($entry);

        $this->fixture = new Builder($this->extractor, $this->hydrator, $feed);

        $post = \Mockery::mock('MamuzBlog\Entity\Post');
        $options = array('foo');
        $this->extractor->shouldReceive('extract')->with($post)->andReturn($options);
        $this->hydrator->shouldReceive('hydrate')->andReturnUsing(
            function ($data, $object) use ($options, $feed, $entryFirst, $entryLast) {
                if ($object instanceof \Zend\Feed\Writer\Feed) {
                    $this->assertSame($feed, $object);
                } else {
                    $this->assertInstanceOf('Zend\Feed\Writer\Entry', $object);
                    if ($this->iteratorCnt == 0) {
                        $object = $entryFirst;
                    } else {
                        $object = $entryLast;
                    }
                    $this->iteratorCnt++;
                }
                $this->assertSame($options, $data);
                return $object;
            }
        );

        $posts = new \ArrayObject(array($post, $post));

        $this->assertInstanceOf('Zend\Feed\Writer\Feed', $this->fixture->create($options, $posts));
    }

    public function testCreationWithUserFeedWithoutEntries()
    {
        $feed = \Mockery::mock('Zend\Feed\Writer\Feed');
        $feed->shouldReceive('setType')->with('rss');
        $feed->shouldReceive('setLastBuildDate');
        $feed->shouldReceive('setGenerator');

        $this->fixture = new Builder($this->extractor, $this->hydrator, $feed);
        $options = array('foo');
        $this->hydrator->shouldReceive('hydrate')->andReturnUsing(
            function ($data, $object) use ($options, $feed) {
                if ($object instanceof \Zend\Feed\Writer\Feed) {
                    $this->assertSame($feed, $object);
                } else {
                    $this->assertInstanceOf('Zend\Feed\Writer\Entry', $object);
                }
                $this->assertSame($options, $data);
                return $object;
            }
        );

        $this->assertInstanceOf('Zend\Feed\Writer\Feed', $this->fixture->create($options));
    }
}
