<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\User;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
  $permissions = [
    'role-list',
    'role-create',
    'role-edit',
    'role-delete',
    'admin',
    'role-admin',
    'user-admin',
    'video-admin',
    'moderator'
    ];


 $u = User::create(['name' => 'admin','email' => 'admin@admin.admin','password' => Hash::make('admin')]);
 //$u->assignRole($permissions);
 foreach ($permissions as $permission) {

      Permission::create(['name' => $permission]);

 }
 $u->syncPermissions($permissions);
 $u->save();
    }
}
