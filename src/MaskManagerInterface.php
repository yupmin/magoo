<?php

namespace Pachico\Magoo;

use Pachico\Magoo\Mask\MaskInterface;

/**
 * Interface Magoo and compatibles must implement
 */
interface MaskManagerInterface
{
    /**
     * Resets Magoo by clearing all previously added masks
     *
     * @return MaskManagerInterface
     */
    public function reset(): self;

    /**
     * Adds a custom mask instance
     *
     * @param MaskInterface $customMask
     *
     * @return MaskManagerInterface
     */
    public function pushMask(MaskInterface $customMask): self;

    /**
     * Applies all masks to a given string
     *
     * @param string $input Input string to be masked
     *
     * @return string Masked string
     */
    public function getMasked(string $input): string;
}
