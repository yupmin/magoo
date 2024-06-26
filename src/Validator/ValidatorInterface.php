<?php

declare(strict_types=1);

namespace Pachico\Magoo\Validator;

/**
 * Validators must implement this interface
 */
interface ValidatorInterface
{
    /**
     * @param string|int $input
     *
     * @return bool If sequence is valid Luhn
     */
    public function isValid($input): bool;
}
