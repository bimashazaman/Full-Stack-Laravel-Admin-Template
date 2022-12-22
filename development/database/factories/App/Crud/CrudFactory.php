<?php

namespace Database\Factories\App\Crud;

use App\Models\App\Crud\Crud;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class CrudFactory extends Factory
{
    protected $model = Crud::class;

    #[ArrayShape(['name' => "string", 'email' => "string", 'phone' => "string", 'gender' => "mixed", 'age' => "int", 'status' => "mixed"])]
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'age' => $this->faker->numberBetween(18, 60),
            'status' => $this->faker->randomElement(['active', 'inactive', 'invited'])
        ];
    }
}
