<?php

namespace MamuzBlogFeedTest\Hydrator;

use MamuzBlogFeed\Hydrator\Mutator;
use Zend\Feed\Writer\Entry;

class MutatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var Mutator */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new Mutator;
    }

    public function testImplementingHydrationInterface()
    {
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydrationInterface', $this->fixture);
    }

    public function testHydration()
    {
        $object = new Entry;

        $data = array(
            'id'     => 'foo',
            'author' => array('name' => 'bar'),
        );

        $this->fixture->hydrate($data, $object);

        $this->assertSame($data['id'], $object->getId());
        $this->assertSame(array($data['author']), $object->getAuthors());
    }
}
