<?php

declare(strict_types=1);

namespace Tests\Unit\DTOs;

use App\DTOs\SalesmanData;
use PHPUnit\Framework\TestCase;

final class SalesmanDataTest extends TestCase
{
    public function testValidSalesmanData(): void
    {
        $data = new SalesmanData(
            firstName: 'John',
            lastName: 'Doe',
            titlesBefore: ['Ing.'],
            titlesAfter: ['PhD.'],
            prosightId: '12345',
            email: 'john.doe@example.com',
            phone: '+421123456789',
            gender: 'm',
            maritalStatus: 'single'
        );

        $this->assertEquals('John', $data->firstName);
        $this->assertEquals('Doe', $data->lastName);
        $this->assertEquals(['Ing.'], $data->titlesBefore);
        $this->assertEquals(['PhD.'], $data->titlesAfter);
        $this->assertEquals('12345', $data->prosightId);
        $this->assertEquals('john.doe@example.com', $data->email);
        $this->assertEquals('+421123456789', $data->phone);
        $this->assertEquals('m', $data->gender);
        $this->assertEquals('single', $data->maritalStatus);
    }

    public function testGetDisplayName(): void
    {
        $data = new SalesmanData(
            firstName: 'John',
            lastName: 'Doe',
            titlesBefore: ['Ing.'],
            titlesAfter: ['PhD.'],
            prosightId: '12345',
            email: 'john.doe@example.com',
            phone: '+421123456789',
            gender: 'm',
            maritalStatus: 'single'
        );

        $this->assertEquals('Ing. John Doe PhD.', $data->getDisplayName());
    }

    public function testGetDisplayNameWithoutTitles(): void
    {
        $data = new SalesmanData(
            firstName: 'John',
            lastName: 'Doe',
            titlesBefore: null,
            titlesAfter: null,
            prosightId: '12345',
            email: 'john.doe@example.com',
            phone: '+421123456789',
            gender: 'm',
            maritalStatus: 'single'
        );

        $this->assertEquals('John Doe', $data->getDisplayName());
    }

    public function testToArray(): void
    {
        $data = new SalesmanData(
            firstName: 'John',
            lastName: 'Doe',
            titlesBefore: ['Ing.'],
            titlesAfter: ['PhD.'],
            prosightId: '12345',
            email: 'john.doe@example.com',
            phone: '+421123456789',
            gender: 'm',
            maritalStatus: 'single'
        );

        $array = $data->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('John', $array['first_name']);
        $this->assertEquals('Doe', $array['last_name']);
        $this->assertEquals(['Ing.'], $array['titles_before']);
        $this->assertEquals(['PhD.'], $array['titles_after']);
        $this->assertEquals('12345', $array['prosight_id']);
        $this->assertEquals('john.doe@example.com', $array['email']);
        $this->assertEquals('+421123456789', $array['phone']);
        $this->assertEquals('m', $array['gender']);
        $this->assertEquals('single', $array['marital_status']);
    }

    public function testFromArray(): void
    {
        $array = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'titles_before' => ['Ing.'],
            'titles_after' => ['PhD.'],
            'prosight_id' => '12345',
            'email' => 'john.doe@example.com',
            'phone' => '+421123456789',
            'gender' => 'm',
            'marital_status' => 'single',
        ];

        $data = SalesmanData::fromArray($array);

        $this->assertEquals('John', $data->firstName);
        $this->assertEquals('Doe', $data->lastName);
        $this->assertEquals(['Ing.'], $data->titlesBefore);
        $this->assertEquals(['PhD.'], $data->titlesAfter);
        $this->assertEquals('12345', $data->prosightId);
        $this->assertEquals('john.doe@example.com', $data->email);
        $this->assertEquals('+421123456789', $data->phone);
        $this->assertEquals('m', $data->gender);
        $this->assertEquals('single', $data->maritalStatus);
    }

    public function testInvalidFirstName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First name must be between 2 and 50 characters');

        new SalesmanData(
            firstName: 'J', // Too short
            lastName: 'Doe',
            titlesBefore: null,
            titlesAfter: null,
            prosightId: '12345',
            email: 'john.doe@example.com',
            phone: null,
            gender: 'm',
            maritalStatus: null
        );
    }

    public function testInvalidProsightId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Prosight ID must be exactly 5 characters');

        new SalesmanData(
            firstName: 'John',
            lastName: 'Doe',
            titlesBefore: null,
            titlesAfter: null,
            prosightId: '123', // Wrong length
            email: 'john.doe@example.com',
            phone: null,
            gender: 'm',
            maritalStatus: null
        );
    }

    public function testInvalidGender(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid gender: invalid');

        SalesmanData::fromArray([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'prosight_id' => '12345',
            'email' => 'john.doe@example.com',
            'gender' => 'invalid',
        ]);
    }
}
