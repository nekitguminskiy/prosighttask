<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @phpstan-type MaritalStatusCode = 'single' | 'married' | 'divorced' | 'widowed'
 * @phpstan-type GenderCode = 'm' | 'f'
 */
enum MaritalStatus: string
{
    case SINGLE = 'single';
    case MARRIED = 'married';
    case DIVORCED = 'divorced';
    case WIDOWED = 'widowed';

    /**
     * @return array<GenderCode, array<MaritalStatusCode, string>>
     */
    public static function getOptions(): array
    {
        return [
            'm' => [
                self::SINGLE->value => 'slobodný',
                self::MARRIED->value => 'ženatý',
                self::DIVORCED->value => 'rozvedený',
                self::WIDOWED->value => 'vdovec',
            ],
            'f' => [
                self::SINGLE->value => 'slobodná',
                self::MARRIED->value => 'vydatá',
                self::DIVORCED->value => 'rozvedená',
                self::WIDOWED->value => 'vdova',
            ],
        ];
    }

    /**
     * @return array<int, array{
     *     code: MaritalStatusCode,
     *     name: array{general: string, m: string, f: string}
     * }>
     */
    public static function toArray(): array
    {
        $options = self::getOptions();
        $generalNames = [
            self::SINGLE->value => 'slobodný / slobodná',
            self::MARRIED->value => 'ženatý / vydatá',
            self::DIVORCED->value => 'rozvedený / rozvedená',
            self::WIDOWED->value => 'vdovec / vdova',
        ];

        return array_map(
            fn(self $status) => [
                'code' => $status->value,
                'name' => [
                    'general' => $generalNames[$status->value],
                    'm' => $options['m'][$status->value],
                    'f' => $options['f'][$status->value],
                ],
            ],
            self::cases()
        );
    }

    /**
     * @param GenderCode $gender
     * @return string
     */
    public function getDisplayName(string $gender): string
    {
        return self::getOptions()[$gender][$this->value];
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
