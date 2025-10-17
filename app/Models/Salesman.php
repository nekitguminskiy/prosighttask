<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\TitleAfter;
use App\Enums\TitleBefore;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @phpstan-type GenderCode = 'm' | 'f'
 * @phpstan-type MaritalStatusCode = 'single' | 'married' | 'divorced' | 'widowed'
 * @phpstan-type TitleBeforeCode = 'Bc.' | 'Mgr.' | 'Ing.' | 'JUDr.' | 'MVDr.' | 'MUDr.' | 'PaedDr.' | 'prof.' | 'doc.' | 'dipl.' | 'MDDr.' | 'Dr.' | 'Mgr. art.' | 'ThLic.' | 'PhDr.' | 'PhMr.' | 'RNDr.' | 'ThDr.' | 'RSDr.' | 'arch.' | 'PharmDr.'
 * @phpstan-type TitleAfterCode = 'CSc.' | 'DrSc.' | 'PhD.' | 'ArtD.' | 'DiS' | 'DiS.art' | 'FEBO' | 'MPH' | 'BSBA' | 'MBA' | 'DBA' | 'MHA' | 'FCCA' | 'MSc.' | 'FEBU' | 'LL.M'
 *
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property array<int, TitleBeforeCode>|null $titles_before
 * @property array<int, TitleAfterCode>|null $titles_after
 * @property string $prosight_id
 * @property string $email
 * @property string|null $phone
 * @property GenderCode $gender
 * @property MaritalStatusCode|null $marital_status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Salesman extends Model
{
    /** @use HasFactory<\Database\Factories\SalesmanFactory> */
    use HasFactory, HasUuids;

    protected $table = 'salesmen';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'titles_before',
        'titles_after',
        'prosight_id',
        'email',
        'phone',
        'gender',
        'marital_status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'titles_before' => 'array',
        'titles_after' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['id'];
    }

    /**
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        $parts = [];

        if ($this->titles_before !== null) {
            $parts = array_merge($parts, $this->titles_before);
        }

        $parts[] = $this->first_name;
        $parts[] = $this->last_name;

        if ($this->titles_after !== null) {
            $parts = array_merge($parts, $this->titles_after);
        }

        return implode(' ', $parts);
    }

    /**
     * @return string
     */
    public function getSelfAttribute(): string
    {
        return "/salesmen/{$this->id}";
    }

    /**
     * Scope for filtering by gender
     *
     * @param \Illuminate\Database\Eloquent\Builder<Salesman> $query
     * @param GenderCode $gender
     * @return \Illuminate\Database\Eloquent\Builder<Salesman>
     */
    public function scopeByGender($query, string $gender): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('gender', $gender);
    }

    /**
     * Scope for filtering by marital status
     *
     * @param \Illuminate\Database\Eloquent\Builder<Salesman> $query
     * @param string $maritalStatus
     * @return \Illuminate\Database\Eloquent\Builder<Salesman>
     */
    public function scopeByMaritalStatus($query, string $maritalStatus): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('marital_status', $maritalStatus);
    }

    /**
     * Scope for searching by name
     *
     * @param \Illuminate\Database\Eloquent\Builder<Salesman> $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder<Salesman>
     */
    public function scopeSearch($query, string $search): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'ILIKE', "%{$search}%")
              ->orWhere('last_name', 'ILIKE', "%{$search}%")
              ->orWhere('email', 'ILIKE', "%{$search}%")
              ->orWhere('prosight_id', 'ILIKE', "%{$search}%");
        });
    }
}
