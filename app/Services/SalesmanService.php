<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\SalesmanRepositoryInterface;
use App\Contracts\Services\SalesmanServiceInterface;
use App\DTOs\SalesmanData;
use App\Exceptions\SalesmanAlreadyExistsException;
use App\Exceptions\SalesmanNotFoundException;
use App\Models\Salesman;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class SalesmanService implements SalesmanServiceInterface
{
    public function __construct(
        private readonly SalesmanRepositoryInterface $salesmanRepository
    ) {
    }

    /**
     * @throws SalesmanAlreadyExistsException
     */
    public function create(SalesmanData $data): Salesman
    {
        $this->validateUniqueConstraints($data);

        $salesman = new Salesman($data->toArray());

        return $this->salesmanRepository->create($salesman);
    }

    /**
     * @throws SalesmanNotFoundException
     * @throws SalesmanAlreadyExistsException
     */
    public function update(string $id, SalesmanData $data): Salesman
    {
        $salesman = $this->findById($id);

        $this->validateUniqueConstraints($data, $id);

        $salesman->fill($data->toArray());

        return $this->salesmanRepository->update($salesman);
    }

    /**
     * @throws SalesmanNotFoundException
     */
    public function findById(string $id): Salesman
    {
        $salesman = $this->salesmanRepository->findById($id);

        if ($salesman === null) {
            throw new SalesmanNotFoundException($id);
        }

        return $salesman;
    }

    /**
     * @param array<string, mixed> $filters
     * @param string|null $sort
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator<int, Salesman>
     */
    public function paginate(array $filters = [], ?string $sort = null, int $page = 1, int $perPage = 15): LengthAwarePaginator
    {
        return $this->salesmanRepository->paginate($filters, $sort, $page, $perPage);
    }

    /**
     * @throws SalesmanNotFoundException
     */
    public function delete(string $id): bool
    {
        $salesman = $this->findById($id);

        return $this->salesmanRepository->delete($salesman->id);
    }

    /**
     * @throws SalesmanAlreadyExistsException
     */
    private function validateUniqueConstraints(SalesmanData $data, ?string $excludeId = null): void
    {
        if ($this->salesmanRepository->existsByProsightId($data->prosightId, $excludeId)) {
            throw new SalesmanAlreadyExistsException('prosight_id', $data->prosightId);
        }

        if ($this->salesmanRepository->existsByEmail($data->email, $excludeId)) {
            throw new SalesmanAlreadyExistsException('email', $data->email);
        }
    }
}
