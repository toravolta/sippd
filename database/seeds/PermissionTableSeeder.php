<?php

use Illuminate\Database\Seeder;
use App\Permission;
use Illuminate\Support\Facades\Artisan;

class PermissionTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['name' => 'role-list', 'label' => 'List', 'group' => 'Role'],
            ['name' =>'role-create', 'label' => 'Create', 'group' => 'Role'],
            ['name' =>'role-edit', 'label' => 'Edit', 'group' => 'Role'],
            ['name' =>'role-delete', 'label' => 'Delete', 'group' => 'Role'],
            ['name' =>'user-list', 'label' => 'List', 'group' => 'User'],
            ['name' =>'user-create', 'label' => 'Create', 'group' => 'User'],
            ['name' =>'user-edit', 'label' => 'Edit', 'group' => 'User'],
            ['name' =>'user-delete', 'label' => 'Delete', 'group' => 'User'],
            ['name' =>'register-list', 'label' => 'List', 'group' => 'Register'],
            ['name' =>'register-create', 'label' => 'Create', 'group' => 'Register'],
            ['name' =>'register-edit', 'label' => 'Edit', 'group' => 'Register'],
            ['name' =>'register-delete', 'label' => 'Delete', 'group' => 'Register'],
            ['name' =>'permission-list', 'label' => 'List', 'group' => 'Permission'],
            ['name' =>'permission-create', 'label' => 'Create', 'group' => 'Permission'],
            ['name' =>'permission-edit', 'label' => 'Edit', 'group' => 'Permission'],
            ['name' =>'permission-delete', 'label' => 'Delete', 'group' => 'Permission'],
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'label' => $permission['label'],
                'guard_name' => 'web',
                'group' => $permission['group']
            ]);
        }

        Artisan::call('permission:cache-reset');
    }
}
