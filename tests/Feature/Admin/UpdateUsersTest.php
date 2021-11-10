<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'name' => 'Pepe',
        'email'=> 'emilio@email.es',
        'password' => '123456',
        'profession_id' => '',
        'bio' => 'Programador de Laravel y Vue.js',
        'twitter' => 'https://twitter.com/pepe',
        'role' => 'user',
    ];


    /** @test */
    public function it_loads_the_edit_users_page(){
        $user = factory(User::class)->create();

        $this->get('usuarios/'.$user->id.'/editar')
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee('Editar Usuario')
            ->assertViewHas('user', function ($viewUser) use ($user){
                return $viewUser->id === $user->id;
            });
    }

    /** @test */
    public function it_updates_a_users(){
        $user = factory(User::class)->create();

        $this->put('usuarios/'. $user->id, $this->withData())->assertRedirect('usuarios/'.$user->id);

        $this->assertCredentials([
            'name' => 'Pepe',
            'email' => 'emilio@email.es',
            'password' => '123456'
        ]);
    }

    /** @test */
    public function the_name_is_required()
    {
        $this->handleValidationExceptions();
        $user = factory(User::class)->create();

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->withData([
                'name' => '',
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('users',['email' => 'aaaaaa@email.com']);
    }

    /** @test */
    public function the_email_is_required()
    {
        $this->handleValidationExceptions();
        $user = factory(User::class)->create();

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->withData([
                'email' => '',
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')
            ->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users',['name' => 'Pepe']);
    }

    /** @test */
    public function the_email_must_be_valid()
    {
        $this->handleValidationExceptions();
        $user = factory(User::class)->create();

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->withData([
                'email' => 'correo_no_valido'
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')
            ->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users',['name' => 'Pepe']);
    }

    /** @test */
    public function the_email_must_be_unique()
    {
        $this->handleValidationExceptions();
        factory(User::class)->create([
            'email' => 'existing_email@email.es'
        ]);

        $user = factory(User::class)->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->withData([
                'email'=> 'existing_email@email.es',
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_password_is_optional()
    {

        $oldPassword = 'CLAVE ANTERIOR';
        $user = factory(User::class)->create([
            'password' => bcrypt($oldPassword)
        ]);
        //$this->withoutExceptionHandling();
        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->withData([
                'password' => '',
            ]))->assertRedirect('usuarios/'.$user->id);

        $this->assertCredentials([
            'name' => 'Pepe',
            'email'=> 'emilio@email.es',
            'password' => $oldPassword,
        ]);

    }

    /** @test */
    public function the_user_email_can_stay_the_same()
    {

        $user = factory(User::class)->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->withData([
                'email'=> 'pepe@mail.es',
            ]))->assertRedirect('usuarios/'.$user->id);

        $this->assertDatabaseHas('users', [
            'name' => 'Pepe',
            'email'=> 'pepe@mail.es',
        ]);
    }

}
