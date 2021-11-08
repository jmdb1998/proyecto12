<?php

namespace Tests\Feature;

use App\Profession;
use App\Skill;
use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;

    private  $profession;

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
        $profession = factory(Profession::class)->create();
        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->get('usuarios/crear')
            ->assertStatus(200)
            ->assertSee('crear un nuevo usuario')
            ->assertViewHas('professions', function ($professions) use ($profession){
                return $professions->contains($profession);
            })->assertViewHas('skills',function ($skills) use ($skillA, $skillB){
                return $skills->contains($skillA) && $skills->contains($skillB);
            });
    }

    /** @test */
    public function it_display_a_404_error_if_user_not_found()
    {
        $this->get('usuarios/999')->assertStatus(404)->assertSee('Página no encontrada');
    }

    /** @test */
    public function it_creates_a_new_user()
    {
        $this->post('usuarios', $this->getValidData())->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Pepe',
            'email'=> 'emilio@email.es',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/pepe',
            'user_id' => User::findByEmail('emilio@email.es')->id,
            'profession_id' => $this->profession->id
        ]);
    }

    /** @test */
    public function the_name_is_required()
    {
        $this->from('usuarios/crear')
            ->post('usuarios', $this->getValidData([
                'name' => '',
            ]))->assertRedirect('usuarios/crear')
        ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
   public function the_email_is_required()
   {
       //$this->withoutExceptionHandling();

       $this->from('usuarios/crear')
           ->post('usuarios', $this->getValidData([
               'email' => '',
           ]))->assertRedirect('usuarios/crear')
           ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

       $this->assertDatabaseEmpty('users');
   }

    /** @test */
    public function the_email_must_be_valid()
    {
        $this->from('usuarios/crear')
            ->post('usuarios', $this->getValidData([
                'email'=> 'correo-no-valido',
            ]))->assertRedirect('usuarios/crear')
            ->assertSessionHasErrors('email');

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_email_must_be_unique()
    {
        //$this->withoutExceptionHandling();
        factory(User::class)->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->from('usuarios/crear')
            ->post('usuarios', $this->getValidData([
                'email' => 'pepe@mail.es',
            ]))->assertRedirect('usuarios/crear')
            ->assertSessionHasErrors('email');
        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function the_password_is_required()
    {
        //$this->withoutExceptionHandling();

        $this->from('usuarios/crear')
            ->post('usuarios', $this->getValidData([
                'password' => '',
            ]))->assertRedirect('usuarios/crear')
            ->assertSessionHasErrors(['password' => 'El campo password es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    public function the_profession_id_field_its_optional()
    {

        $this->post('usuarios', $this->getValidData([
            'profession_id' => null
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Pepe',
            'email'=> 'emilio@email.es',
            'password' => '123456',

        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Programador de Laravel y Vue.js',
            'user_id' => User::findByEmail('emilio@email.es')->id,
            'profession_id' => null
        ]);
    }
    /** @test */
    public function the_profession_must_be_valid(){

        $this->from('usuarios/crear')
            ->post('usuarios', $this->getValidData([
                'profession_id' => '999',
            ]))->assertRedirect('usuarios/crear')
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');

    }

    /** @test */
    public function only_not_deleted_professions_can_be_selected(){
        $deletedProfession = factory(Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d'),
        ]);

        $this->from('usuarios/crear')
            ->post('usuarios', $this->getValidData([
                'profession_id' => $deletedProfession->id,
            ]))->assertRedirect('usuarios/crear')
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

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

        $this->put('usuarios/'. $user->id, $this->getValidData())->assertRedirect('usuarios/'.$user->id);

        $this->assertCredentials([
            'name' => 'Pepe',
            'email' => 'emilio@email.es',
            'password' => '123456'
        ]);
    }

    /** @test */
    public function the_name_is_required_when_updating_a_user()
    {
        //$this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->getValidData([
                'name' => '',
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')->assertSessionHasErrors('name');

        $this->assertDatabaseMissing('users',['email' => 'aaaaaa@email.com']);
    }

    /** @test */
    public function the_email_is_required_when_updating_a_user()
    {
        //$this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->getValidData([
                'email' => '',
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')
            ->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users',['name' => 'Pepe']);
    }

    /** @test */
    public function the_email_must_be_valid_when_updating_a_user()
    {

        $user = factory(User::class)->create();

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->getValidData([
                'email' => 'correo_no_valido'
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')
            ->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users',['name' => 'Pepe']);
    }

    /** @test */
    public function the_email_must_be_unique_when_updating_a_user()
    {
        factory(User::class)->create([
            'email' => 'existing_email@email.es'
        ]);
        //$this->withoutExceptionHandling();
        $user = factory(User::class)->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->getValidData([
                'email'=> 'existing_email@email.es',
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function the_password_is_optional_when_updating_an_user()
    {

        $oldPassword = 'CLAVE ANTERIOR';
      $user = factory(User::class)->create([
          'password' => bcrypt($oldPassword)
      ]);
        //$this->withoutExceptionHandling();
        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->getValidData([
                'password' => '',
            ]))->assertRedirect('usuarios/'.$user->id);

        $this->assertCredentials([
            'name' => 'Pepe',
            'email'=> 'emilio@email.es',
            'password' => $oldPassword,
        ]);

    }

    /** @test */
    public function the_user_email_can_stay_the_same_when_updating_a_user()
    {

        $user = factory(User::class)->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->getValidData([
                'email'=> 'pepe@mail.es',
            ]))->assertRedirect('usuarios/'.$user->id);

        $this->assertDatabaseHas('users', [
            'name' => 'Pepe',
            'email'=> 'pepe@mail.es',
        ]);
    }

    /** @test */
    public function it_deletes_a_user()
    {

        $user = factory(User::class)->create();

        $this->delete('usuarios/'. $user->id)
            ->assertRedirect('usuarios');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        //$this->assertSame(User::count()); Otra forma de comprobarlo
    }

    /** @test */
    public function the_twitter_field_its_optional()
    {
        $this->withoutExceptionHandling();

        $this->post('usuarios', $this->getValidData([
            'twitter' => null
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Pepe',
            'email'=> 'emilio@email.es',
            'password' => '123456'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => null,
            'user_id' => User::findByEmail('emilio@email.es')->id,
        ]);
    }



    public function getValidData(array $custom = []){

        $this->profession = factory(Profession::class)->create();
        return array_merge([
            'name' => 'Pepe',
            'email'=> 'emilio@email.es',
            'password' => '123456',
            'profession_id' => $this->profession->id,
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/pepe'
        ], $custom);
    }
}
