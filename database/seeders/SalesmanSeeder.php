<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\DTOs\SalesmanData;
use App\Models\Salesman;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

final class SalesmanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Clear existing salesmen
            Salesman::query()->delete();

            $csvPath = database_path('seeders/salesmen.csv');

            if (!File::exists($csvPath)) {
                $this->command->error('CSV file not found: ' . $csvPath);
                return;
            }

            $csvData = $this->parseCsvFile($csvPath);

        foreach ($csvData as $row) {
            try {
                $salesmanData = SalesmanData::fromArray($row);
                $salesman = new Salesman($salesmanData->toArray());
                $salesman->save();
            } catch (\Exception $e) {
                $this->command->error('Error creating salesman: ' . $e->getMessage());
                $this->command->error('Row data: ' . json_encode($row));
            }
        }

        $this->command->info('Salesmen seeded successfully!');
        });
    }

    /**
     * @param string $filePath
     * @return array<int, array<string, mixed>>
     */
    private function parseCsvFile(string $filePath): array
    {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false || count($lines) < 2) {
            return [];
        }

        // Parse header
        $header = array_map('trim', explode(';', $lines[0]));

        $data = [];

        for ($i = 1; $i < count($lines); $i++) {
            $values = array_map('trim', explode(';', $lines[$i]));

            if (count($values) !== count($header)) {
                continue;
            }

            $row = array_combine($header, $values);

            // Process titles_before and titles_after
            $titlesBefore = $row['titles_before'] ?? '';
            $titlesAfter = $row['titles_after'] ?? '';

            $row['titles_before'] = $this->parseTitles($titlesBefore);
            $row['titles_after'] = $this->parseTitles($titlesAfter);

            // Convert empty strings to null for nullable fields
            $row['phone'] = empty($row['phone']) ? null : $row['phone'];
            $row['marital_status'] = empty($row['marital_status']) ? null : $row['marital_status'];

            $data[] = $row;
        }

        return $data;
    }

    /**
     * @param string $titlesString
     * @return array<int, string>|null
     */
    private function parseTitles(string $titlesString): ?array
    {
        if (empty($titlesString)) {
            return null;
        }

        // Split by comma and trim each title
        $titles = array_map('trim', explode(',', $titlesString));

        // Filter out empty titles
        $titles = array_filter($titles, fn(string $title) => !empty($title));

        return empty($titles) ? null : array_values($titles);
    }
}
