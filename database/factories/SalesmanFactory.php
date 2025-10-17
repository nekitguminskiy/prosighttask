<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Salesman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salesman>
 */
final class SalesmanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Salesman>
     */
    protected $model = Salesman::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'titles_before' => $this->faker->optional()->randomElements([
                'Ing.', 'Mgr.', 'Dr.', 'Bc.', 'prof.'
            ], $this->faker->numberBetween(1, 2)),
            'titles_after' => $this->faker->optional()->randomElements([
                'PhD.', 'MBA', 'CSc.', 'MSc.'
            ], $this->faker->numberBetween(1, 2)),
            'prosight_id' => $this->faker->unique()->numerify('#####'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'gender' => $this->faker->randomElement(['m', 'f']),
            'marital_status' => $this->faker->optional()->randomElement([
                'single', 'married', 'divorced', 'widowed'
            ]),
        ];
    }

    /**
     * Indicate that the salesman is male.
     */
    public function male(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'm',
        ]);
    }

    /**
     * Indicate that the salesman is female.
     */
    public function female(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'f',
        ]);
    }

    /**
     * Indicate that the salesman is single.
     */
    public function single(): static
    {
        return $this->state(fn (array $attributes) => [
            'marital_status' => 'single',
        ]);
    }

    /**
     * Indicate that the salesman is married.
     */
    public function married(): static
    {
        return $this->state(fn (array $attributes) => [
            'marital_status' => 'married',
        ]);
    }

    /**
     * Indicate that the salesman has titles.
     */
    public function withTitles(): static
    {
        return $this->state(fn (array $attributes) => [
            'titles_before' => $this->faker->randomElements([
                'Ing.', 'Mgr.', 'Dr.', 'Bc.'
            ], $this->faker->numberBetween(1, 2)),
            'titles_after' => $this->faker->randomElements([
                'PhD.', 'MBA', 'CSc.'
            ], $this->faker->numberBetween(1, 2)),
        ]);
    }
}
