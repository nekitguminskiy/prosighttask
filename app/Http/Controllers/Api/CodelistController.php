<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTOs\CodelistData;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class CodelistController extends Controller
{

    /**
     * Display codelists.
     */
    public function __invoke(): JsonResponse
    {
        $codelists = CodelistData::getAll();

        return response()->json($codelists);
    }
}
