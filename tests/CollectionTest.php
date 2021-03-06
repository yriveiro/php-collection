<?php
namespace Yriveiro\Collection\Tests;

use Yriveiro\Collection\Collection;
use PHPUnit_Framework_TestCase as TestCase;
use Yriveiro\Collection\Tests\Resources\Bag;

class CollectionTest extends TestCase
{
    public function testCanExtendCollection()
    {
        $bag = new Bag();
        $statement = is_subclass_of($bag, 'Yriveiro\Collection\Collection');
        $this->assertTrue($statement);
    }

    public function testCanSetItem()
    {
        $collection = new Collection();
        $collection->set('foo', 'Foo');
        $this->assertEquals('Foo', $collection->get('foo'));
    }

    /**
     * @dataProvider provideTestData
     */
    public function testAll($items)
    {
        $collection = new Collection($items);
        $this->assertSame($items, $collection->all());
    }

    /**
     * @dataProvider provideTestData
     */
    public function testCount($items)
    {
        $collection = new Collection($items);
        $this->assertEquals(count($items), $collection->count());
    }

    /**
     * @dataProvider provideTestData
     */
    public function testHas($items)
    {
        $collection = new Collection($items);
        foreach ($items as $key => $value) {
            $this->assertTrue($collection->has($key));
        }
    }

    /**
     * @dataProvider provideTestData
     */
    public function testGet($items)
    {
        $collection = new Collection($items);
        foreach ($items as $key => $value) {
            $this->assertSame($value, $collection->get($key));
        }
    }

    /**
     * @dataProvider provideTestData
     */
    public function testRemove($items)
    {
        $collection = new Collection($items);
        foreach ($items as $key => $value) {
            $collection->remove($key);
            $this->assertArrayNotHasKey($key, $collection->all());
            $this->assertFalse($collection->has($key));
        }
    }

    /**
     * @dataProvider provideTestData
     */
    public function testIsEmpty($items)
    {
        $collection = new Collection($items);
        foreach ($items as $key => $value) {
            $collection->remove($key);
        }

        $this->assertTrue($collection->isEmpty());
    }

    public function testOffsetExists()
    {
        $collection = new Collection(array('foo' => 'var'));
        $this->assertTrue(isset($collection['foo']));
    }

    public function testOffsetGet()
    {
        $collection = new Collection(array('foo' => 'var'));
        $this->assertEquals('var', $collection['foo']);
    }

    public function testOffsetSet()
    {
        $collection = new Collection();
        $collection['foo'] = 'var';

        $this->assertEquals('var', $collection['foo']);
    }

    public function testOffsetUnset()
    {
        $collection = new Collection();
        $collection['foo'] = 'var';

        unset($collection['foo']);

        $this->assertArrayNotHasKey('foo', $collection->all());
    }

    /**
     * @dataProvider eachDataProvider
     */
    public function testEach($actual, $expected)
    {
        $collection = new Collection($actual);

        $collection->each(function ($key, &$value) {
            $value = strtoupper($value);
        });

        $this->assertSame($expected, $collection->all());
    }

    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch($actual, $expected)
    {
        $collection = new Collection($actual);

        $matches = $collection->match('^__prefix');

        $this->assertSame($expected, $matches);
    }

    /**
     * @dataProvider provideTestData
     */
    public function testCanClearCollection($items)
    {
        $collection = new Collection($items);

        $this->assertEquals(count($items), $collection->count());
        $collection->clear();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @dataProvider provideTestData
     */
    public function testCanCountItemsInitialEmptyCollection()
    {
        $collection = new Collection();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @dataProvider provideTestData
     */
    public function testGetIterator($items)
    {
        $collection = new Collection($items);
        $iterations = 0;
        foreach ($collection->getIterator() as $key => $item) {
            $this->assertSame($items[$key], $item);
            $iterations++;
        }
        $this->assertEquals(count($items), $iterations);
    }

    public function testKeys()
    {
        $collection = new Collection(array('foo' => 'bar', 'foo2' => 'bar2'));

        $this->assertSame(array('foo', 'foo2'), $collection->keys());
    }

    public function provideTestData()
    {
        return array(
            'indexed' => array(
                array(1, 2, 3, 4, 5)
            ),
            'associative' => array(
                array('A' => 'a', 'B' => 'b', 'C' => 'c')
            ),
            'mixed' => array(
                array('A' => 'a', 1, 'B' => 'b', 2, 3)
            ),
        );
    }

    public function eachDataProvider()
    {
        return array(
            array(
                'actual' => array('a', 'b', 'c'),
                'expected' => array('A', 'B', 'C')
            )
        );
    }

    public function matchDataProvider()
    {
        return array(
            array(
                'actual' => array(
                    '__prefix_a' => 'a',
                    '__prefix_b' => 'b',
                    'c' => 'c'
                ),
                'expected' => array(
                    '__prefix_a' => 'a',
                    '__prefix_b' => 'b'
                )
            )
        );
    }
}
