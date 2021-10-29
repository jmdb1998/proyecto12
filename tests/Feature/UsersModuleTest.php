<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_loads_the_users_list_page()
    {
        factory(User::class)->create([
            'name'=> 'Joel'
        ]);

        factory(User::class)->create([
            'name'=> 'Ellie'
        ]);

        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee('Usuarios')
            ->assertSee('Joel')
            ->assertSee('Ellie');
    }

    /** @test */

    public function it_shows_a_default_message_if_the_user_list_is_empty()
    {
        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee('Usuarios')
            ->assertSee('No hay usuarios');
    }

    /** @test */
    public function it_displays_the_user_details()
    {
        $user = factory(User::class)->create([
            'name'=> 'Joel'
        ]);

        $this->get('usuarios/'. $user->id)
        ->assertStatus(200)
        ->assertSee($user->name);
    }

    /** @test */
    public function it_loads_the_new_user_page()
    {
        $this->get('usuarios/crear')
            ->assertStatus(200)
            ->assertSee('Crear un Usuario');
    }

    /** @test */
    public function it_display_a_404_error_if_user_not_found()
    {
        $this->get('usuarios/999')->assertStatus(404)->assertSee('Página no encontrada');
    }

    /** @test */
    /*public function it_creates_a_new_user()
    {
        $this->post('usuarios', [
            'name' => 'Pepe',
            'email'=> 'emilio@email.es',
            'password' => '123456'
        ])->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Pepe',
            'email'=> 'emilio@email.es',
            'password' => '123456'
        ]);
    }*/

    /** @test */
   /* public function the_name_is_required()
    {
        $this->from('usuarios/crear')
            ->post('usuarios', [
            'name' => '',
            'email'=> 'emilio@email.es',
            'password' => '123456'
        ])->assertRedirect('usuarios/crear')
        ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertDatabaseMissing('users',['email' => 'emilio@email.es']);

        $this->assertEquals(0, User::count());
    }*/

    /** @test */
   public function the_email_is_required()
   {
       //$this->withoutExceptionHandling();

       $this->from('usuarios/crear')
           ->post('usuarios', [
               'name' => 'Pepe',
               'email'=> '',
               'password' => '123456'
           ])->assertRedirect('usuarios/crear')
           ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

       $this->assertDatabaseMissing('users',['email' => 'pepe@email.com']);

       $this->assertEquals(0, User::count());
   }

    /** @test */
    public function the_password_is_required()
    {
        //$this->withoutExceptionHandling();

        $this->from('usuarios/crear')
            ->post('usuarios', [
                'name' => 'Pepe',
                'email'=> 'pepe@mail.com',
                'password' => ''
            ])->assertRedirect('usuarios/crear')
            ->assertSessionHasErrors(['password' => 'El campo password es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    public function the_email_must_be_valid()
    {
        $this->from('usuarios/crear')
            ->post('usuarios', [
                'name' => 'Pepe',
                'email'=> 'correo-no-valido',
                'password' => '123456'
            ])->assertRedirect('usuarios/crear')
            ->assertSessionHasErrors('email');

        $this->assertEquals(0, User::count());
    }

    /** @test */
    public function the_email_must_be_unique()
    {
        //$this->withoutExceptionHandling();
        factory(User::class)->create([
           'email' => 'pepe@mail.es',
        ]);

        $this->from('usuarios/crear')
            ->post('usuarios', [
                'name' => 'Pepe',
                'email'=> 'pepe@mail.es',
                'password' => '123456'
            ])->assertRedirect('usuarios/crear')
            ->assertSessionHasErrors('email');
        $this->assertEquals(1, User::count());
    }
}
