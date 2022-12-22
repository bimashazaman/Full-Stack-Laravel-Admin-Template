<?php

namespace Database\Factories\Core\Auth;

use App\Models\Core\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Core\Auth\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['first_name' => "string", 'last_name' => "string", 'email' => "string", 'password' => "string", 'status_id' => "\Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed"])]
    public function definition(): array
    {
        $status = Status::query()->where('type', 'user')->pluck('id')->toArray();
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('123456'),
            'status_id' => $status[array_rand($status)],
        ];
    }
}
