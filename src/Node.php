<?php

declare(strict_types=1);

namespace ShipMonk;

class Node
{
    public ?Node $next = null;

    public function __construct(public int|string $value)
    {
    }
}
