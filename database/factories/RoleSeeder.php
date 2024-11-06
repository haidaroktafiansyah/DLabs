<?php

namespace Database\Factories;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['code' => 'superadmin', 'privileges' => 'create,edit,delete'],
            ['code' => 'admin', 'privileges' => 'create,edit'],
            ['code' => 'editor', 'privileges' => 'view,edit'],
            ['code' => 'viewer', 'privileges' => 'view'],
            ['code' => 'moderator', 'privileges' => 'create,edit'],
            ['code' => 'contributor', 'privileges' => 'view,edit'],
            ['code' => 'manager', 'privileges' => 'create,edit,delete'],
            ['code' => 'support', 'privileges' => 'view'],
            ['code' => 'guest', 'privileges' => 'view'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['code' => $role['code']],
                ['privileges' => $role['privileges']]
            );
        }
    }
}
