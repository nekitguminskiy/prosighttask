<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Tests\TestCase;

final class CodelistControllerTest extends TestCase
{
    public function testIndexReturnsCodelists(): void
    {
        $response = $this->getJson('/api/v1/codelists');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'marital_statuses' => [
                    '*' => [
                        'code',
                        'name' => [
                            'general',
                            'm',
                            'f',
                        ],
                    ],
                ],
                'genders' => [
                    '*' => [
                        'code',
                        'name',
                    ],
                ],
                'titles_before' => [
                    '*' => [
                        'code',
                        'name',
                    ],
                ],
                'titles_after' => [
                    '*' => [
                        'code',
                        'name',
                    ],
                ],
            ]);
    }

    public function testCodelistsContainExpectedValues(): void
    {
        $response = $this->getJson('/api/v1/codelists');

        $response->assertStatus(200);

        $data = $response->json();

        // Check genders
        $genderCodes = array_column($data['genders'], 'code');
        $this->assertContains('m', $genderCodes);
        $this->assertContains('f', $genderCodes);

        // Check marital statuses
        $maritalStatusCodes = array_column($data['marital_statuses'], 'code');
        $this->assertContains('single', $maritalStatusCodes);
        $this->assertContains('married', $maritalStatusCodes);
        $this->assertContains('divorced', $maritalStatusCodes);
        $this->assertContains('widowed', $maritalStatusCodes);

        // Check titles before
        $titleBeforeCodes = array_column($data['titles_before'], 'code');
        $this->assertContains('Ing.', $titleBeforeCodes);
        $this->assertContains('Mgr.', $titleBeforeCodes);
        $this->assertContains('Dr.', $titleBeforeCodes);

        // Check titles after
        $titleAfterCodes = array_column($data['titles_after'], 'code');
        $this->assertContains('PhD.', $titleAfterCodes);
        $this->assertContains('MBA', $titleAfterCodes);
        $this->assertContains('CSc.', $titleAfterCodes);
    }
}
