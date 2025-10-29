<?php

declare(strict_types=1);

namespace Unit\Collections\LinkedList;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ShipMonk\Collections\LinkedList\Node;
use TypeError;

#[CoversClass(Node::class)]
class NodeTest extends TestCase
{
    public function testConstructorWithIntValue(): void
    {
        $node = new Node(42);

        $this->assertSame(42, $node->value);
        $this->assertNull($node->next);
    }

    public function testConstructorWithStringValue(): void
    {
        $node = new Node('hello');

        $this->assertSame('hello', $node->value);
        $this->assertNull($node->next);
    }

    public function testConstructorWithEmptyString(): void
    {
        $node = new Node('');

        $this->assertSame('', $node->value);
        $this->assertNull($node->next);
    }

    public function testWithNullValue(): void
    {
        $this->expectException(TypeError::class);
        new Node(null);
    }
}