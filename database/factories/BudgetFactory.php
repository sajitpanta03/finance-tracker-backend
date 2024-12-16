<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 year', 'now'); // Start date within the last year
        $endDate = fake()->dateTimeBetween($startDate, '+6 months'); // End date after start date
        return [
            'name'=> fake()->name(),
            'amount'=>fake()->randomFloat(2, 100, 1000),
            'start_date'=>$startDate->format('Y-m-d'), // Format as 'YYYY-MM-DD'
            'end_date' => $endDate->format('Y-m-d'), // Format as 'YYYY-MM-DD'
        ];
    }
}
