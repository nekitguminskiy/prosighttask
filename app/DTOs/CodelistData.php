<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\TitleAfter;
use App\Enums\TitleBefore;

/**
 * @phpstan-type GenderCode = 'm' | 'f'
 * @phpstan-type MaritalStatusCode = 'single' | 'married' | 'divorced' | 'widowed'
 * @phpstan-type TitleBeforeCode = 'Bc.' | 'Mgr.' | 'Ing.' | 'JUDr.' | 'MVDr.' | 'MUDr.' | 'PaedDr.' | 'prof.' | 'doc.' | 'dipl.' | 'MDDr.' | 'Dr.' | 'Mgr. art.' | 'ThLic.' | 'PhDr.' | 'PhMr.' | 'RNDr.' | 'ThDr.' | 'RSDr.' | 'arch.' | 'PharmDr.'
 * @phpstan-type TitleAfterCode = 'CSc.' | 'DrSc.' | 'PhD.' | 'ArtD.' | 'DiS' | 'DiS.art' | 'FEBO' | 'MPH' | 'BSBA' | 'MBA' | 'DBA' | 'MHA' | 'FCCA' | 'MSc.' | 'FEBU' | 'LL.M'
 */
final readonly class CodelistData
{
    /**
     * @return array{
     *     marital_statuses: array<int, array{
     *         code: MaritalStatusCode,
     *         name: array{general: string, m: string, f: string}
     *     }>,
     *     genders: array<int, array{code: GenderCode, name: string}>,
     *     titles_before: array<int, array{code: TitleBeforeCode, name: TitleBeforeCode}>,
     *     titles_after: array<int, array{code: TitleAfterCode, name: TitleAfterCode}>
     * }
     */
    public static function getAll(): array
    {
        return [
            'marital_statuses' => MaritalStatus::toArray(),
            'genders' => Gender::toArray(),
            'titles_before' => TitleBefore::toArray(),
            'titles_after' => TitleAfter::toArray(),
        ];
    }
}
