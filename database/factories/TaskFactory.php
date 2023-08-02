<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
   
    public function definition(): array
    {
        $title = $this->faker->sentence();
        return [
            'title'   => $title,
            'description' => $this->faker->text,
            'due_date'  => now(),
        ];
    }
}
