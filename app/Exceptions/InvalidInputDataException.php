<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class InvalidInputDataException extends Exception
{
    /**
     * @param string $field
     * @param string $value
     * @param string $expectedType
     */
    public function __construct(string $field, string $value, string $expectedType)
    {
        parent::__construct(
            message: "Bad format of input data. Field {$field} {$value} must be of type {$expectedType}.",
            code: Response::HTTP_BAD_REQUEST
        );
    }
}
