<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Salesman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SalesmanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsSalesmenList(): void
    {
        Salesman::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'prosight_id' => '12345',
            'email' => 'john.doe@example.com',
            'gender' => 'm',
        ]);

        $response = $this->getJson('/api/v1/salesmen');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'self',
                        'first_name',
                        'last_name',
                        'display_name',
                        'titles_before',
                        'titles_after',
                        'prosight_id',
                        'email',
                        'phone',
                        'gender',
                        'marital_status',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
            ]);
    }

    public function testStoreCreatesSalesman(): void
    {
        $data = [
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

        $response = $this->postJson('/api/v1/salesmen', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'self',
                    'first_name',
                    'last_name',
                    'display_name',
                    'titles_before',
                    'titles_after',
                    'prosight_id',
                    'email',
                    'phone',
                    'gender',
                    'marital_status',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('salesmen', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'prosight_id' => '12345',
            'email' => 'john.doe@example.com',
            'gender' => 'm',
        ]);
    }

    public function testStoreValidatesRequiredFields(): void
    {
        $response = $this->postJson('/api/v1/salesmen', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'prosight_id',
                'email',
                'gender',
            ]);
    }

    public function testStoreValidatesUniqueConstraints(): void
    {
        Salesman::factory()->create([
            'prosight_id' => '12345',
            'email' => 'john.doe@example.com',
        ]);

        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'prosight_id' => '12345', // Duplicate
            'email' => 'jane.doe@example.com',
            'gender' => 'f',
        ];

        $response = $this->postJson('/api/v1/salesmen', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['prosight_id']);
    }

    public function testShowReturnsSalesman(): void
    {
        $salesman = Salesman::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $response = $this->getJson("/api/v1/salesmen/{$salesman->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $salesman->id,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
            ]);
    }

    public function testShowReturnsNotFoundForInvalidId(): void
    {
        $response = $this->getJson('/api/v1/salesmen/00000000-0000-0000-0000-000000000000');

        $response->assertStatus(404)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'PERSON_NOT_FOUND',
                    ],
                ],
            ]);
    }

    public function testUpdateModifiesSalesman(): void
    {
        $salesman = Salesman::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'prosight_id' => '12345',
            'email' => 'john.doe@example.com',
        ]);

        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'prosight_id' => '12345',
            'email' => 'john.doe@example.com',
            'gender' => 'm',
        ];

        $response = $this->putJson("/api/v1/salesmen/{$salesman->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $salesman->id,
                    'first_name' => 'Jane',
                    'last_name' => 'Smith',
                ],
            ]);

        $this->assertDatabaseHas('salesmen', [
            'id' => $salesman->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);
    }

    public function testDeleteRemovesSalesman(): void
    {
        $salesman = Salesman::factory()->create();

        $response = $this->deleteJson("/api/v1/salesmen/{$salesman->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('salesmen', [
            'id' => $salesman->id,
        ]);
    }

    public function testDeleteReturnsNotFoundForInvalidId(): void
    {
        $response = $this->deleteJson('/api/v1/salesmen/00000000-0000-0000-0000-000000000000');

        $response->assertStatus(404);
    }

    public function testIndexWithPagination(): void
    {
        Salesman::factory()->count(25)->create();

        $response = $this->getJson('/api/v1/salesmen?page=2&per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
            ]);

        $this->assertCount(10, $response->json('data'));
    }

    public function testIndexWithSorting(): void
    {
        Salesman::factory()->create(['first_name' => 'Zoe']);
        Salesman::factory()->create(['first_name' => 'Alice']);

        $response = $this->getJson('/api/v1/salesmen?sort=first_name');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals('Alice', $data[0]['first_name']);
        $this->assertEquals('Zoe', $data[1]['first_name']);
    }

    public function testIndexWithDescendingSort(): void
    {
        Salesman::factory()->create(['first_name' => 'Alice']);
        Salesman::factory()->create(['first_name' => 'Zoe']);

        $response = $this->getJson('/api/v1/salesmen?sort=-first_name');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals('Zoe', $data[0]['first_name']);
        $this->assertEquals('Alice', $data[1]['first_name']);
    }
}
