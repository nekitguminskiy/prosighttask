<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\TitleAfter;
use App\Enums\TitleBefore;

/**
 * @phpstan-type TitleBeforeCode = 'Bc.' | 'Mgr.' | 'Ing.' | 'JUDr.' | 'MVDr.' | 'MUDr.' | 'PaedDr.' | 'prof.' | 'doc.' | 'dipl.' | 'MDDr.' | 'Dr.' | 'Mgr. art.' | 'ThLic.' | 'PhDr.' | 'PhMr.' | 'RNDr.' | 'ThDr.' | 'RSDr.' | 'arch.' | 'PharmDr.'
 * @phpstan-type TitleAfterCode = 'CSc.' | 'DrSc.' | 'PhD.' | 'ArtD.' | 'DiS' | 'DiS.art' | 'FEBO' | 'MPH' | 'BSBA' | 'MBA' | 'DBA' | 'MHA' | 'FCCA' | 'MSc.' | 'FEBU' | 'LL.M'
 * @phpstan-type GenderCode = 'm' | 'f'
 * @phpstan-type MaritalStatusCode = 'single' | 'married' | 'divorced' | 'widowed'
 */
final readonly class SalesmanData
{
    /**
     * @param string $firstName
     * @param string $lastName
     * @param array<int, string>|null $titlesBefore
     * @param array<int, string>|null $titlesAfter
     * @param string $prosightId
     * @param string $email
     * @param string|null $phone
     * @param string $gender
     * @param string|null $maritalStatus
     */
    public function __construct(
        public string $firstName,
        public string $lastName,
        public ?array $titlesBefore,
        public ?array $titlesAfter,
        public string $prosightId,
        public string $email,
        public ?string $phone,
        public string $gender,
        public ?string $maritalStatus,
    ) {
        $this->validate();
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validate(): void
    {
        if (strlen($this->firstName) < 2 || strlen($this->firstName) > 50) {
            throw new \InvalidArgumentException('First name must be between 2 and 50 characters');
        }

        if (strlen($this->lastName) < 2 || strlen($this->lastName) > 50) {
            throw new \InvalidArgumentException('Last name must be between 2 and 50 characters');
        }

        if (strlen($this->prosightId) !== 5) {
            throw new \InvalidArgumentException('Prosight ID must be exactly 5 characters');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        if (!Gender::isValid($this->gender)) {
            throw new \InvalidArgumentException('Invalid gender code');
        }

        if ($this->maritalStatus !== null && !MaritalStatus::isValid($this->maritalStatus)) {
            throw new \InvalidArgumentException('Invalid marital status code');
        }

        if ($this->titlesBefore !== null) {
            foreach ($this->titlesBefore as $title) {
                if (!TitleBefore::isValid($title)) {
                    throw new \InvalidArgumentException('Invalid title before code: ' . $title);
                }
            }
        }

        if ($this->titlesAfter !== null) {
            foreach ($this->titlesAfter as $title) {
                if (!TitleAfter::isValid($title)) {
                    throw new \InvalidArgumentException('Invalid title after code: ' . $title);
                }
            }
        }
    }

    /**
     * @return array{
     *     first_name: string,
     *     last_name: string,
     *     titles_before: array<int, string>|null,
     *     titles_after: array<int, string>|null,
     *     prosight_id: string,
     *     email: string,
     *     phone: string|null,
     *     gender: string,
     *     marital_status: string|null
     * }
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'titles_before' => $this->titlesBefore,
            'titles_after' => $this->titlesAfter,
            'prosight_id' => $this->prosightId,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'marital_status' => $this->maritalStatus,
        ];
    }

    public function getDisplayName(): string
    {
        $parts = [];

        if ($this->titlesBefore !== null) {
            $parts = array_merge($parts, $this->titlesBefore);
        }

        $parts[] = $this->firstName;
        $parts[] = $this->lastName;

        if ($this->titlesAfter !== null) {
            $parts = array_merge($parts, $this->titlesAfter);
        }

        return implode(' ', $parts);
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     * @throws \InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        $titlesBefore = null;
        if (isset($data['titles_before']) && is_array($data['titles_before'])) {
            $filtered = array_filter($data['titles_before'], fn($title) => is_string($title));
            $validTitles = TitleBefore::filterValid(array_values($filtered));
            if ($validTitles !== null) {
                $titlesBefore = $validTitles;
            }
        }

        $titlesAfter = null;
        if (isset($data['titles_after']) && is_array($data['titles_after'])) {
            $filtered = array_filter($data['titles_after'], fn($title) => is_string($title));
            $validTitles = TitleAfter::filterValid(array_values($filtered));
            if ($validTitles !== null) {
                $titlesAfter = $validTitles;
            }
        }

        $gender = $data['gender'];
        if (!is_string($gender) || !in_array($gender, ['f', 'm'], true)) {
            $genderValue = is_string($gender) ? $gender : gettype($gender);
            throw new \InvalidArgumentException("Invalid gender: {$genderValue}");
        }

        $maritalStatus = isset($data['marital_status']) && is_string($data['marital_status']) ? $data['marital_status'] : null;
        if ($maritalStatus !== null && !in_array($maritalStatus, ['single', 'married', 'divorced', 'widowed'], true)) {
            throw new \InvalidArgumentException("Invalid marital status: {$maritalStatus}");
        }

        $firstName = $data['first_name'];
        if (!is_string($firstName)) {
            throw new \InvalidArgumentException("first_name must be a string");
        }

        $lastName = $data['last_name'];
        if (!is_string($lastName)) {
            throw new \InvalidArgumentException("last_name must be a string");
        }

        $prosightId = $data['prosight_id'];
        if (!is_string($prosightId)) {
            throw new \InvalidArgumentException("prosight_id must be a string");
        }

        $email = $data['email'];
        if (!is_string($email)) {
            throw new \InvalidArgumentException("email must be a string");
        }

        return new self(
            firstName: $firstName,
            lastName: $lastName,
            titlesBefore: $titlesBefore,
            titlesAfter: $titlesAfter,
            prosightId: $prosightId,
            email: $email,
            phone: isset($data['phone']) && is_string($data['phone']) ? $data['phone'] : null,
            gender: $gender,
            maritalStatus: $maritalStatus,
        );
    }
}
