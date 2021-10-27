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
    public function it_creates_a_new_user()
    {
        $this->post('usuarios', [
            'name' => 'Pepe',
            'email'=> 'pepe@email.es',
            'password' => '123456'
        ])->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Pepe',
            'email'=> 'pepe@email.es',
            'password' => '123456'
        ]);
    }

    /** @test */
    public function the_name_is_required()
    {
        //$this->withoutExceptionHandling();

        $this->post('usuarios', [
            'name' => '',
            'email'=> 'pepe@email.es',
            'password' => '123456'
        ])->assertRedirect('usuarios/crear')
        ->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('users', [
            'email'=> 'pepe@email.es'
        ]);
    }
}
