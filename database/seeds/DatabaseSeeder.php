<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        //$this->call(UsersTableSeeder::class);
       // $this->call(RolesTableSeeder::class);
       $this->call(TeamsTableSeeder::class);
    }

}

class UsersTableSeeder extends Seeder {

    public function run() {
        App\User::create([
            'name' => 'admin',
            'email' => 'admin@admin.ru',
            'password' => bcrypt('admin'),
        ]);
    }

}

class RolesTableSeeder extends Seeder {

    public function run() {
        App\Models\Role::create(
                [
                    'caption' => 'Администратор',
                    'description' => 'Администратор',
                    'slug'=>'admin'
        ]);
                App\Models\Role::create(
                [
                    'caption' => 'Авторизованный',
                    'description' => 'Авторизованный',
                    'slug'=>'auth'
        ]);
    }

}
class TeamsTableSeeder extends Seeder {
    public function run()
    {
           App\Models\Team::create(
            [
                'name'=>'ЦСКА',
            ]
            );
             App\Models\Team::create(
            [
                'name'=>'Спартак',
            ]
            );
               App\Models\Team::create(
            [
                'name'=>'Динамо',
            ]
            );
    }
 
}
