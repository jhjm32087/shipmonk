<?php

declare(strict_types=1);

namespace ShipMonk\Collections\LinkedList;

enum Type
{
    case STRING;
    case INTEGER;

    public static function fromValue(int|string $value): self
    {
        $getType = gettype($value);
        return match (true) {
            $getType === 'integer' => Type::INTEGER,
            $getType === 'string' => Type::STRING,
        };
    }
}
