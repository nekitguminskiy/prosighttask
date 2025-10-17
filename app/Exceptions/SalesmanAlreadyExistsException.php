<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class SalesmanAlreadyExistsException extends Exception
{
    public function __construct(string $field, string $value)
    {
        parent::__construct(
            message: "Salesman with such {$field} {$value} is already registered.",
            code: Response::HTTP_CONFLICT
        );
    }
}
