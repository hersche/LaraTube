<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $userRole = config('roles.models.role')::where('name', '=', 'User')->first();
        $adminRole = config('roles.models.role')::where('name', '=', 'Admin')->first();
        $permissions = config('roles.models.permission')::all();

        /*
         * Add Users
         *
         */
        if (config('roles.defaultUserModel')::where('email', '=', 'admin@admin.com')->first() === null) {
            $newUser = config('roles.defaultUserModel')::create([
                'name'     => 'Admin',
                'email'    => 'admin@admin.com',
            ]);

            $newUser->attachRole($adminRole);
            foreach ($permissions as $permission) {
                $newUser->attachPermission($permission);
            }
        }

        if (config('roles.defaultUserModel')::where('email', '=', 'user@user.com')->first() === null) {
            $newUser = config('roles.defaultUserModel')::create([
                'name'     => 'User',
                'email'    => 'user@user.com',
            ]);

            $newUser;
            $newUser->attachRole($userRole);
        }
    }
}
