<?php

declare(strict_types=1);

namespace Pachico\Magoo;

/**
 * MagooArray acts as Magoo but for (multidimensional) trying to convert
 * to string everything it can and masking it.
 */
class MagooArray
{
    protected MaskManagerInterface $maskManager;

    public function __construct(MaskManagerInterface $maskManager)
    {
        $this->maskManager = $maskManager;
    }

    /**
     * @param mixed $input
     *
     * @return string|object
     */
    protected function maskIndividualValue($input)
    {
        switch (gettype($input)) {
            case 'array':
                $input = $this->maskMultiDimensionalStructure($input);
                break;
            case 'string':
            case 'float':
            case 'double':
            case 'int':
                $input = $this->maskManager->getMasked((string) $input);
                break;
            case 'object':
                if (method_exists($input, '__toString')) {
                    $input = $this->maskManager->getMasked((string) $input);
                }
                break;
        }

        return $input;
    }

    protected function maskMultiDimensionalStructure(array $input): array
    {
        foreach ($input as &$value) {
            $value = $this->maskIndividualValue($value);
        }

        return $input;
    }

    public function getMaskManager(): MaskManagerInterface
    {
        return $this->maskManager;
    }

    public function getMasked(array $input): array
    {
        return $this->maskMultiDimensionalStructure($input);
    }
}
