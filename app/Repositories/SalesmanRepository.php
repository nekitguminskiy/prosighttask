<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\SalesmanRepositoryInterface;
use App\Models\Salesman;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class SalesmanRepository implements SalesmanRepositoryInterface
{
    public function findById(string $id): ?Salesman
    {
        /** @var Salesman|null $salesman */
        $salesman = Salesman::query()->find($id);

        return $salesman;
    }

    public function findByProsightId(string $prosightId): ?Salesman
    {
        /** @var Salesman|null $salesman */
        $salesman = Salesman::query()
            ->where('prosight_id', $prosightId)
            ->first();

        return $salesman;
    }

    public function findByEmail(string $email): ?Salesman
    {
        /** @var Salesman|null $salesman */
        $salesman = Salesman::query()
            ->where('email', $email)
            ->first();

        return $salesman;
    }

    public function create(Salesman $salesman): Salesman
    {
        $salesman->save();

        return $salesman;
    }

    public function update(Salesman $salesman): Salesman
    {
        $salesman->save();

        return $salesman;
    }

    public function delete(string $id): bool
    {
        return Salesman::query()->where('id', $id)->delete() > 0;
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
        $query = $this->buildQuery($filters);
        
        if ($sort !== null) {
            $query = $this->applySorting($query, $sort);
        }

        /** @var LengthAwarePaginator<int, Salesman> $paginator */
        $paginator = $query->paginate(
            perPage: $perPage,
            page: $page,
            pageName: 'page'
        );

        return $paginator;
    }

    /**
     * @param array<string, mixed> $filters
     * @return Collection<int, Salesman>
     */
    public function getAll(array $filters = []): Collection
    {
        $query = $this->buildQuery($filters);

        /** @var Collection<int, Salesman> $collection */
        $collection = $query->get();

        return $collection;
    }

    public function existsByProsightId(string $prosightId, ?string $excludeId = null): bool
    {
        $query = Salesman::query()->where('prosight_id', $prosightId);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function existsByEmail(string $email, ?string $excludeId = null): bool
    {
        $query = Salesman::query()->where('email', $email);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * @param array<string, mixed> $filters
     * @return Builder<Salesman>
     */
    private function buildQuery(array $filters): Builder
    {
        $query = Salesman::query();

        if (isset($filters['search']) && is_string($filters['search'])) {
            $query->search($filters['search']);
        }

        if (isset($filters['gender']) && is_string($filters['gender']) && in_array($filters['gender'], ['m', 'f'], true)) {
            $query->byGender($filters['gender']);
        }

        if (isset($filters['marital_status']) && is_string($filters['marital_status']) && in_array($filters['marital_status'], ['single', 'married', 'divorced', 'widowed'], true)) {
            $query->byMaritalStatus($filters['marital_status']);
        }

        if (isset($filters['prosight_id']) && is_string($filters['prosight_id'])) {
            $query->where('prosight_id', 'ILIKE', "%{$filters['prosight_id']}%");
        }

        return $query;
    }

    /**
     * @param Builder<Salesman> $query
     * @param string $sort
     * @return Builder<Salesman>
     */
    private function applySorting(Builder $query, string $sort): Builder
    {
        $direction = 'asc';
        $field = $sort;

        if (str_starts_with($sort, '-')) {
            $direction = 'desc';
            $field = substr($sort, 1);
        }

        $allowedFields = [
            'first_name',
            'last_name',
            'prosight_id',
            'email',
            'gender',
            'marital_status',
            'created_at',
            'updated_at',
        ];

        if (in_array($field, $allowedFields, true)) {
            $query->orderBy($field, $direction);
        }

        return $query;
    }
}
