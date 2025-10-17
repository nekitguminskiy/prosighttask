<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Salesman;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * @phpstan-type GenderCode = 'm' | 'f'
 * @phpstan-type MaritalStatusCode = 'single' | 'married' | 'divorced' | 'widowed'
 */
interface SalesmanRepositoryInterface
{
    /**
     * @param string $id
     * @return Salesman|null
     */
    public function findById(string $id): ?Salesman;

    /**
     * @param string $prosightId
     * @return Salesman|null
     */
    public function findByProsightId(string $prosightId): ?Salesman;

    /**
     * @param string $email
     * @return Salesman|null
     */
    public function findByEmail(string $email): ?Salesman;

    /**
     * @param Salesman $salesman
     * @return Salesman
     */
    public function create(Salesman $salesman): Salesman;

    /**
     * @param Salesman $salesman
     * @return Salesman
     */
    public function update(Salesman $salesman): Salesman;

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * @param array<string, mixed> $filters
     * @param string|null $sort
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator<int, Salesman>
     */
    public function paginate(array $filters = [], ?string $sort = null, int $page = 1, int $perPage = 15): LengthAwarePaginator;

    /**
     * @param array<string, mixed> $filters
     * @return Collection<int, Salesman>
     */
    public function getAll(array $filters = []): Collection;

    /**
     * @param string $prosightId
     * @param string|null $excludeId
     * @return bool
     */
    public function existsByProsightId(string $prosightId, ?string $excludeId = null): bool;

    /**
     * @param string $email
     * @param string|null $excludeId
     * @return bool
     */
    public function existsByEmail(string $email, ?string $excludeId = null): bool;
}
