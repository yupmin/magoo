<?php

declare(strict_types=1);

namespace Pachico\MagooTest\Mask;

use Pachico\Magoo\Mask\MaskInterface;

class CustomMask implements MaskInterface
{
    protected string $replacement = '*';

    public function __construct(array $params = [])
    {
        if (isset($params['replacement']) && is_string($params['replacement'])) {
            $this->replacement = $params['replacement'];
        }
    }

    public function mask(string $string): string
    {
        return str_replace('foo', $this->replacement, $string);
    }
}
