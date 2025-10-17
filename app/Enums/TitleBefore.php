<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @phpstan-type TitleBeforeCode = 'Bc.' | 'Mgr.' | 'Ing.' | 'JUDr.' | 'MVDr.' | 'MUDr.' | 'PaedDr.' | 'prof.' | 'doc.' | 'dipl.' | 'MDDr.' | 'Dr.' | 'Mgr. art.' | 'ThLic.' | 'PhDr.' | 'PhMr.' | 'RNDr.' | 'ThDr.' | 'RSDr.' | 'arch.' | 'PharmDr.'
 */
enum TitleBefore: string
{
    case BC = 'Bc.';
    case MGR = 'Mgr.';
    case ING = 'Ing.';
    case JUDR = 'JUDr.';
    case MVDR = 'MVDr.';
    case MUDR = 'MUDr.';
    case PAEDDR = 'PaedDr.';
    case PROF = 'prof.';
    case DOC = 'doc.';
    case DIPL = 'dipl.';
    case MDDR = 'MDDr.';
    case DR = 'Dr.';
    case MGR_ART = 'Mgr. art.';
    case THLIC = 'ThLic.';
    case PHDR = 'PhDr.';
    case PHMR = 'PhMr.';
    case RNDR = 'RNDr.';
    case THDR = 'ThDr.';
    case RSDR = 'RSDr.';
    case ARCH = 'arch.';
    case PHARMDR = 'PharmDr.';

    /**
     * @return array<TitleBeforeCode, TitleBeforeCode>
     */
    public static function getOptions(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_column(self::cases(), 'value')
        );
    }

    /**
     * @return array<int, array{code: TitleBeforeCode, name: TitleBeforeCode}>
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
     * @return array<int, TitleBeforeCode>|null
     */
    public static function filterValid(array $values): ?array
    {
        $valid = [];
        foreach ($values as $value) {
            if (self::isValid($value)) {
                /** @var TitleBeforeCode $value */
                $valid[] = $value;
            }
        }
        return empty($valid) ? null : $valid;
    }
}
