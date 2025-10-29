<?php

declare(strict_types=1);

namespace Unit\Collections\LinkedList;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ShipMonk\Collections\LinkedList\Node;
use ShipMonk\Collections\LinkedList\SortedLinkedList;
use ShipMonk\Collections\LinkedList\Type;

#[CoversClass(SortedLinkedList::class)]
#[CoversClass(Node::class)]
class SortedLinkedListTest extends TestCase
{
    public function testCannotMixIntegerAndString(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot insert STRING value into a list of INTEGER values");

        $list->insert("test");
    }

    public function testCannotMixStringAndInteger(): void
    {
        $list = new SortedLinkedList();
        $list->insert("test");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot insert INTEGER value into a list of STRING values");

        $list->insert(5);
    }

    public function testIsEmptyOnNewList(): void
    {
        $list = new SortedLinkedList();
        $this->assertTrue($list->isEmpty());
        $this->assertEquals(0, $list->count());
    }

    public function testIsEmptyAfterInsert(): void
    {
        $list = new SortedLinkedList();
        $list->insert(42);
        $this->assertFalse($list->isEmpty());
        $this->assertEquals(1, $list->count());
    }

    public function testContains(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);

        $this->assertTrue($list->contains(5));
        $this->assertTrue($list->contains(2));
        $this->assertTrue($list->contains(8));
        $this->assertFalse($list->contains(10));
        $this->assertFalse($list->contains(1));
    }

    public function testRemove(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);
        $list->insert(1);

        $this->assertTrue($list->remove(5));
        $this->assertEquals([1, 2, 8], $list->toArray());
        $this->assertEquals(3, $list->count());

        $this->assertFalse($list->remove(10));
        $this->assertEquals([1, 2, 8], $list->toArray());
        $this->assertEquals(3, $list->count());
    }

    public function testRemoveFromHead(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);

        $this->assertTrue($list->remove(2));
        $this->assertEquals([5, 8], $list->toArray());
    }

    public function testRemoveFromTail(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);

        $this->assertTrue($list->remove(8));
        $this->assertEquals([2, 5], $list->toArray());
    }

    public function testRemoveFromEmptyList(): void
    {
        $list = new SortedLinkedList();
        $this->assertFalse($list->remove(5));
    }

    public function testClear(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);

        $list->clear();

        $this->assertTrue($list->isEmpty());
        $this->assertEquals(0, $list->count());
        $this->assertEquals([], $list->toArray());
        $this->assertNull($list->getType());
    }

    public function testClearAllowsDifferentTypeAfter(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->clear();
        $list->insert("test");

        $this->assertEquals(["test"], $list->toArray());
        $this->assertEquals(Type::STRING, $list->getType());
    }

    public function testGetType(): void
    {
        $list = new SortedLinkedList();
        $this->assertNull($list->getType());

        $list->insert(5);
        $this->assertEquals(Type::INTEGER, $list->getType());

        $list2 = new SortedLinkedList();
        $list2->insert("test");
        $this->assertEquals(Type::STRING, $list2->getType());
    }

    public function testIteratorWithIntegers(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);
        $list->insert(1);

        $result = [];
        foreach ($list as $key => $value) {
            $result[$key] = $value;
        }

        $this->assertEquals([0 => 1, 1 => 2, 2 => 5, 3 => 8], $result);
    }

    public function testIteratorWithStrings(): void
    {
        $list = new SortedLinkedList();
        $list->insert("dog");
        $list->insert("apple");
        $list->insert("cat");

        $result = [];
        foreach ($list as $value) {
            $result[] = $value;
        }

        $this->assertEquals(["apple", "cat", "dog"], $result);
    }

    public function testIteratorOnEmptyList(): void
    {
        $list = new SortedLinkedList();

        $count = 0;
        foreach ($list as $value) {
            $count++;
        }

        $this->assertEquals(0, $count);
    }

    public function testMultipleIterations(): void
    {
        $list = new SortedLinkedList();
        $list->insert(3);
        $list->insert(1);
        $list->insert(2);

        $result1 = [];
        foreach ($list as $value) {
            $result1[] = $value;
        }

        $result2 = [];
        foreach ($list as $value) {
            $result2[] = $value;
        }

        $this->assertEquals([1, 2, 3], $result1);
        $this->assertEquals([1, 2, 3], $result2);
    }

    public function testInsertDuplicates(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);

        $this->assertEquals([2, 2, 5, 5, 8], $list->toArray());
        $this->assertEquals(5, $list->count());
    }

    public function testRemoveDuplicateOnlyRemovesFirst(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(5);
        $list->insert(2);

        $this->assertTrue($list->remove(5));
        $this->assertEquals([2, 2, 5], $list->toArray());
        $this->assertEquals(3, $list->count());
    }

    public function testSingleElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(42);

        $this->assertEquals([42], $list->toArray());
        $this->assertEquals(1, $list->count());
        $this->assertTrue($list->contains(42));
    }

    public function testInsertAtBeginning(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(3);
        $list->insert(1);

        $this->assertEquals([1, 3, 5], $list->toArray());
    }

    public function testInsertAtEnd(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(3);
        $list->insert(5);

        $this->assertEquals([1, 3, 5], $list->toArray());
    }

    public function testGetFirstElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);

        $this->assertEquals(2, $list->get(0));
    }

    public function testGetLastElement(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);

        $this->assertEquals(8, $list->get(2));
    }

    public function testGetWithStrings(): void
    {
        $list = new SortedLinkedList();
        $list->insert("dog");
        $list->insert("apple");
        $list->insert("cat");

        $this->assertEquals("apple", $list->get(0));
        $this->assertEquals("cat", $list->get(1));
        $this->assertEquals("dog", $list->get(2));
    }
}
