<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class SalesmanNotFoundException extends Exception
{
    public function __construct(string $uuid)
    {
        parent::__construct(
            message: "Salesman \"{$uuid}\" not found.",
            code: Response::HTTP_NOT_FOUND
        );
    }
}
