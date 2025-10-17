<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @phpstan-type GenderCode = 'm' | 'f'
 */
enum Gender: string
{
    case MALE = 'm';
    case FEMALE = 'f';

    /**
     * @return array<GenderCode, string>
     */
    public static function getOptions(): array
    {
        return [
            self::MALE->value => 'muž',
            self::FEMALE->value => 'žena',
        ];
    }

    /**
     * @return array<int, array{code: GenderCode, name: string}>
     */
    public static function toArray(): array
    {
        return array_map(
            fn(self $gender) => [
                'code' => $gender->value,
                'name' => self::getOptions()[$gender->value],
            ],
            self::cases()
        );
    }

    public function getDisplayName(): string
    {
        return self::getOptions()[$this->value];
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, array_column(self::cases(), 'value'), true);
    }
}
