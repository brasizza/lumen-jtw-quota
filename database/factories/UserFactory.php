<?php

namespace Database\Factories;

use App\Http\Controllers\AuthController;
use App\Models\User;
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
        $auth = new AuthController();
        return [
            'name' => 'superUser',
            'email' => 'superuser@superuser.com',
            'password' => $this->faker->password(4,20),
            'client_id' => $auth->generateApiKey(),
            'client_secret' => $auth->generateApiKey(),
            'is_admin' => true
        ];
    }
}
