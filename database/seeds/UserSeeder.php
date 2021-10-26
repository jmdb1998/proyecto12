<?php

use App\Profession;
use App\User;
use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*$professionId = DB::table('professions')
            ->whereTitle('Desarrollador Front-End')
            ->value('id');
            'profession_id' => $professionId Otra forma de hacerlo menos eficiente
        */

       // dd($profession); muestra el valor de la variable al ejecutar los seeders

        /*DB::table('users')->insert([
            'name' => 'Pepe Viyuela',
            'email' => 'pepe@email.com',
            'password' => bcrypt('123456'),
            'profession_id' => DB::table('professions')->whereTitle('Desarrollador Front-End')->value('id')
        ]);*/

        User::create([
            'name' => 'Pepe Viyuela',
            'email' => 'pepe@email.com',
            'password' => bcrypt('123456'),
            'profession_id' => Profession::whereTitle('Desarrollador Front-End')->value('id'),
            'is_admin' => true
        ]);

        /*User::create([
            'name' => 'Juan Martinez',
            'email' => 'juan@email.com',
            'password' => bcrypt('123456'),
            'profession_id' => Profession::whereTitle('Desarrollador Front-End')->value('id'),
        ]);

        User::create([
            'name' => 'Jaime Sanchez',
            'email' => 'jaime@email.com',
            'password' => bcrypt('123456'),
            'profession_id' => null,
        ]);*/

        factory(User::class)->create([
            'profession_id' => Profession::whereTitle('Desarrollador Back-End')->value('id'),
        ]);

        factory(User::class, 48)->create();
    }
}
