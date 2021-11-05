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
        $profession_id = Profession::whereTitle('Desarrollador Back-End')->value('id');

        $user = User::create([
            'name' => 'Pepe Viyuela',
            'email' => 'pepe@email.com',
            'password' => bcrypt('123456'),
            'is_admin' => true
        ]);

        $user->profile()->create([
            'bio' => 'Programador',
            'profession_id' => $profession_id
        ]);

        factory(User::class, 49)->create()->each(function ($user){
            $user->profile()->create(
                factory(App\UserProfile::class)->raw()
            );
        });
    }
}
