<?php

declare(strict_types=1);

namespace Pachico\MagooTest\Dummy;

/**
 * Dummy class to be used in test suite only
 */
class Stringable
{
    protected $value;

    /**
     * Dummy class with __toString implementation
     * for test purposes
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
