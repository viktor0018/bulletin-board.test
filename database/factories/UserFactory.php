<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\UserStatus;
use App\Models\UserRole;

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
        return [
            'name' => $this->faker->firstNameMale,
            'surname' => $this->faker->lastName,
            'middlename' => $this->faker->middleNameMale,
            'user_role_id' => UserRole::all()->random()->id,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' =>  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password , Hash::make ( $this->faker->password())
            'phone' => $this->faker->phoneNumber,
            'phone_access_time' =>   $this->faker->boolean() ? $this->faker->time('h:m').'-'.$this->faker->time('h:m') : null,
            'user_status_id' => UserStatus::all()->random()->id,
            'remember_token' => Str::random(10),
        ];
    }
}
