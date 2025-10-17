<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions.
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse
     */
    private function handleApiException(Request $request, Throwable $e): JsonResponse
    {
        if ($e instanceof SalesmanNotFoundException) {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'PERSON_NOT_FOUND',
                        'message' => $e->getMessage(),
                    ],
                ],
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof SalesmanAlreadyExistsException) {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'PERSON_ALREADY_EXISTS',
                        'message' => $e->getMessage(),
                    ],
                ],
            ], Response::HTTP_CONFLICT);
        }

        if ($e instanceof InvalidInputDataException) {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'INPUT_DATA_BAD_FORMAT',
                        'message' => $e->getMessage(),
                    ],
                ],
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($e instanceof InputDataOutOfRangeException) {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'INPUT_DATA_OUT_OF_RANGE',
                        'message' => $e->getMessage(),
                    ],
                ],
            ], Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'INPUT_DATA_BAD_FORMAT',
                        'message' => 'Bad format of input data.',
                    ],
                ],
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'BAD_REQUEST',
                        'message' => 'Query execution failed.',
                    ],
                ],
            ], Response::HTTP_BAD_REQUEST);
        }

        // For other exceptions, return a generic error
        return response()->json([
            'errors' => [
                [
                    'code' => 'INTERNAL_SERVER_ERROR',
                    'message' => 'An internal server error occurred.',
                ],
            ],
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
