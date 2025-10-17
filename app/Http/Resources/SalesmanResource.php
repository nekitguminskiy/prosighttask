<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Salesman
 * @property-read string $id
 * @property-read string $self
 * @property-read string $first_name
 * @property-read string $last_name
 * @property-read string $display_name
 * @property-read array<int, string>|null $titles_before
 * @property-read array<int, string>|null $titles_after
 * @property-read string $prosight_id
 * @property-read string $email
 * @property-read string|null $phone
 * @property-read string $gender
 * @property-read string|null $marital_status
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 */
final class SalesmanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array{
     *     id: string,
     *     self: string,
     *     first_name: string,
     *     last_name: string,
     *     display_name: string,
     *     titles_before: array<int, string>|null,
     *     titles_after: array<int, string>|null,
     *     prosight_id: string,
     *     email: string,
     *     phone: string|null,
     *     gender: string,
     *     marital_status: string|null,
     *     created_at: string,
     *     updated_at: string
     * }
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'self' => $this->self,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'display_name' => $this->display_name,
            'titles_before' => $this->titles_before ? array_values($this->titles_before) : null,
            'titles_after' => $this->titles_after ? array_values($this->titles_after) : null,
            'prosight_id' => $this->prosight_id,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'created_at' => (string) $this->created_at->toISOString(),
            'updated_at' => (string) $this->updated_at->toISOString(),
        ];
    }
}
