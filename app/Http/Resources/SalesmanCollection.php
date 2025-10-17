<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class SalesmanCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array{
     *     data: \Illuminate\Http\Resources\Json\AnonymousResourceCollection,
     *     links: array{
     *         first: string|null,
     *         last: string|null,
     *         prev: string|null,
     *         next: string|null
     *     }
     * }
     */
    public function toArray(Request $request): array
    {
        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator<int, \App\Models\Salesman> $paginator */
        $paginator = $this->resource;

        return [
            'data' => SalesmanResource::collection($this->collection),
            'links' => [
                'first' => $paginator->url(1) ? parse_url($paginator->url(1), PHP_URL_PATH) . '?' . parse_url($paginator->url(1), PHP_URL_QUERY) : null,
                'last' => $paginator->url($paginator->lastPage()) ? parse_url($paginator->url($paginator->lastPage()), PHP_URL_PATH) . '?' . parse_url($paginator->url($paginator->lastPage()), PHP_URL_QUERY) : null,
                'prev' => $paginator->previousPageUrl() ? parse_url($paginator->previousPageUrl(), PHP_URL_PATH) . '?' . parse_url($paginator->previousPageUrl(), PHP_URL_QUERY) : null,
                'next' => $paginator->nextPageUrl() ? parse_url($paginator->nextPageUrl(), PHP_URL_PATH) . '?' . parse_url($paginator->nextPageUrl(), PHP_URL_QUERY) : null,
            ],
        ];
    }
}
