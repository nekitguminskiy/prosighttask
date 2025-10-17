<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @phpstan-type TitleAfterCode = 'CSc.' | 'DrSc.' | 'PhD.' | 'ArtD.' | 'DiS' | 'DiS.art' | 'FEBO' | 'MPH' | 'BSBA' | 'MBA' | 'DBA' | 'MHA' | 'FCCA' | 'MSc.' | 'FEBU' | 'LL.M'
 */
enum TitleAfter: string
{
    case CSC = 'CSc.';
    case DRSC = 'DrSc.';
    case PHD = 'PhD.';
    case ARTD = 'ArtD.';
    case DIS = 'DiS';
    case DIS_ART = 'DiS.art';
    case FEBO = 'FEBO';
    case MPH = 'MPH';
    case BSBA = 'BSBA';
    case MBA = 'MBA';
    case DBA = 'DBA';
    case MHA = 'MHA';
    case FCCA = 'FCCA';
    case MSC = 'MSc.';
    case FEBU = 'FEBU';
    case LLM = 'LL.M';

    /**
     * @return array<TitleAfterCode, TitleAfterCode>
     */
    public static function getOptions(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_column(self::cases(), 'value')
        );
    }

    /**
     * @return array<int, array{code: TitleAfterCode, name: TitleAfterCode}>
     */
    public static function toArray(): array
    {
        return array_map(
            fn(self $title) => [
                'code' => $title->value,
                'name' => $title->value,
            ],
            self::cases()
        );
    }

    public function getDisplayName(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, array_column(self::cases(), 'value'), true);
    }

    /**
     * @param array<int, string> $values
     * @return array<int, TitleAfterCode>|null
     */
    public static function filterValid(array $values): ?array
    {
        $valid = [];
        foreach ($values as $value) {
            if (self::isValid($value)) {
                /** @var TitleAfterCode $value */
                $valid[] = $value;
            }
        }
        return empty($valid) ? null : $valid;
    }
}
