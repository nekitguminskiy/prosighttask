<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\DTOs\SalesmanData;
use App\Models\Salesman;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @phpstan-type GenderCode = 'm' | 'f'
 * @phpstan-type MaritalStatusCode = 'single' | 'married' | 'divorced' | 'widowed'
 */
interface SalesmanServiceInterface
{
    /**
     * @param SalesmanData $data
     * @return Salesman
     * @throws \App\Exceptions\SalesmanAlreadyExistsException
     */
    public function create(SalesmanData $data): Salesman;

    /**
     * @param string $id
     * @param SalesmanData $data
     * @return Salesman
     * @throws \App\Exceptions\SalesmanNotFoundException
     * @throws \App\Exceptions\SalesmanAlreadyExistsException
     */
    public function update(string $id, SalesmanData $data): Salesman;

    /**
     * @param string $id
     * @return Salesman
     * @throws \App\Exceptions\SalesmanNotFoundException
     */
    public function findById(string $id): Salesman;

    /**
     * @param array<string, mixed> $filters
     * @param string|null $sort
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator<int, Salesman>
     */
    public function paginate(array $filters = [], ?string $sort = null, int $page = 1, int $perPage = 15): LengthAwarePaginator;

    /**
     * @param string $id
     * @return bool
     * @throws \App\Exceptions\SalesmanNotFoundException
     */
    public function delete(string $id): bool;
}
