<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowUsersTest extends TestCase
{
    use RefreshDatabase;

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
    public function it_display_a_404_error_if_user_not_found()
    {
        $this->withExceptionHandling();
        $this->get('usuarios/999')->assertStatus(404)->assertSee('Página no encontrada');
    }
}
