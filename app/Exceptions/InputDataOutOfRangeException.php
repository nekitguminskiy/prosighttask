<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class InputDataOutOfRangeException extends Exception
{
    /**
     * @param string $field
     * @param string $value
     * @param string $range
     */
    public function __construct(string $field, string $value, string $range)
    {
        parent::__construct(
            message: "Input data out of range. Field {$field} of value {$value} is out of range. Acceptable range for this field is {$range}.",
            code: Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE
        );
    }
}
