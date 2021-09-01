<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Base Permission
        $this->call(PermissionTableSeeder::class);

        // User Admin
        $this->call(AdminUserSeeder::class);
    }
}
