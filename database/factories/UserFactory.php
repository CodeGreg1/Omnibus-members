<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Modules\Users\Support\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();

        return [
            'first_name'     => $firstName,
            'last_name'     => $lastName,
            'username'     => strtolower($firstName . $lastName),
            'email'     => $this->faker->unique()->safeEmail(),
            'password'     => 'p@ssword',
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
        ];
    }
}
