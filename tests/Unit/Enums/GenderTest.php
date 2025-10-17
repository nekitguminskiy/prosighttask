<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\Gender;
use PHPUnit\Framework\TestCase;

final class GenderTest extends TestCase
{
    public function testGenderValues(): void
    {
        $this->assertEquals('m', Gender::MALE->value);
        $this->assertEquals('f', Gender::FEMALE->value);
    }

    public function testGetOptions(): void
    {
        $options = Gender::getOptions();

        $this->assertIsArray($options);
        $this->assertEquals('muž', $options['m']);
        $this->assertEquals('žena', $options['f']);
    }

    public function testToArray(): void
    {
        $array = Gender::toArray();

        $this->assertIsArray($array);
        $this->assertCount(2, $array);

        $this->assertEquals('m', $array[0]['code']);
        $this->assertEquals('muž', $array[0]['name']);

        $this->assertEquals('f', $array[1]['code']);
        $this->assertEquals('žena', $array[1]['name']);
    }

    public function testGetDisplayName(): void
    {
        $this->assertEquals('muž', Gender::MALE->getDisplayName());
        $this->assertEquals('žena', Gender::FEMALE->getDisplayName());
    }

    public function testIsValid(): void
    {
        $this->assertTrue(Gender::isValid('m'));
        $this->assertTrue(Gender::isValid('f'));
        $this->assertFalse(Gender::isValid('invalid'));
        $this->assertFalse(Gender::isValid(''));
    }
}
