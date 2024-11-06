<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $roles = [
            ['code' => 'super_admin', 'privileges' => 'create,edit,delete'],
            ['code' => 'admin', 'privileges' => 'create,edit'],
            ['code' => 'editor', 'privileges' => 'view,edit'],
            ['code' => 'viewer', 'privileges' => 'view'],
            ['code' => 'moderator', 'privileges' => 'create,edit'],
            ['code' => 'contributor', 'privileges' => 'view,edit'],
            ['code' => 'manager', 'privileges' => 'create,edit,delete'],
            ['code' => 'support', 'privileges' => 'view'],
            ['code' => 'guest', 'privileges' => 'view'],
        ];

        $role = $this->faker->randomElement($roles);

        return [
            'code' => $role['code'],
            'privileges' => $role['privileges'],
        ];
    }
}
