<?php

namespace Pachico\Magoo\Mask;

/**
 * Masks must implement this interface since
 * mask() method will be executed for all of them
 */
interface MaskInterface
{
    public function __construct(array $params = []);

    /**
     * Masks a given string
     */
    public function mask(string $string): string;
}
