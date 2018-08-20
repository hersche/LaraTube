<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
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


     $u = User::create(['name' => 'admin','email' => 'admin@admin.admin','password' => Hash::make('adminadmin')]);
     $u1 = User::create(['name' => 'bla','email' => 'bla@bla.bla','password' => Hash::make('blabla')]);
     $u2 = User::create(['name' => 'ni','email' => 'ni@ni.ni','password' => Hash::make('ninini')]);
      $u3 = User::create(['name' => 'na','email' => 'na@na.na','password' => Hash::make('nanana')]);
     //$u->assignRole($permissions);
     foreach ($permissions as $permission) {

          Permission::create(['name' => $permission]);

     }
     $u->syncPermissions($permissions);
     $u->save();
     $u1->syncPermissions($permissions);
     $u1->save();
     $u2->syncPermissions($permissions);
     $u2->save();
     $u3->syncPermissions($permissions);
     $u3->save();
    }
}
