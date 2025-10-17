<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Contracts\Services\SalesmanServiceInterface;
use App\DTOs\SalesmanData;
use App\Http\Requests\CreateSalesmanRequest;
use App\Http\Requests\UpdateSalesmanRequest;
use App\Http\Resources\SalesmanCollection;
use App\Http\Resources\SalesmanResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

final class SalesmanController extends Controller
{
    public function __construct(
        private readonly SalesmanServiceInterface $salesmanService
    ) {
    }

    /**
     * Display a listing of salesmen.
     */
    public function index(Request $request): JsonResponse
    {
        $pageParam = $request->get('page', 1);
        $perPageParam = $request->get('per_page', 15);

        $page = max(1, is_numeric($pageParam) ? (int) $pageParam : 1);
        $perPage = max(1, is_numeric($perPageParam) ? (int) $perPageParam : 15);

        $sort = $request->get('sort');

        $filters = $request->only(['search', 'gender', 'marital_status', 'prosight_id']);

        // Filter only string values and cast to correct type
        $filters = array_filter($filters, fn($value) => is_string($value));
        /** @var array<string, mixed> $filters */
        $filters = $filters;

        $salesmen = $this->salesmanService->paginate($filters, $sort, $page, $perPage);

        return response()->json(new SalesmanCollection($salesmen));
    }

    /**
     * Store a newly created salesman.
     *
     * @throws ValidationException
     */
    public function store(CreateSalesmanRequest $request): JsonResponse
    {
        $data = SalesmanData::fromArray($request->validated());
        $salesman = $this->salesmanService->create($data);
        return response()->json([
            'data' => new SalesmanResource($salesman)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified salesman.
     */
    public function show(string $salesmanUuid): JsonResponse
    {
        $salesman = $this->salesmanService->findById($salesmanUuid);

        return response()->json([
            'data' => new SalesmanResource($salesman)
        ]);
    }

    /**
     * Update the specified salesman.
     *
     * @throws ValidationException
     */
    public function update(UpdateSalesmanRequest $request, string $salesmanUuid): JsonResponse
    {
        $data = SalesmanData::fromArray($request->validated());
        $salesman = $this->salesmanService->update($salesmanUuid, $data);

        return response()->json([
            'data' => new SalesmanResource($salesman)
        ]);
    }

    /**
     * Remove the specified salesman.
     */
    public function destroy(string $salesmanUuid): Response
    {
        $this->salesmanService->delete($salesmanUuid);

        return response()->noContent();
    }
}
