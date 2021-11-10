<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Skill;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUsersTest extends TestCase
{
    use RefreshDatabase;

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
    public function it_creates_a_new_user()
    {
        $profession = factory(Profession::class)->create();
        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();
        $skillC = factory(Skill::class)->create();

        $this->post('usuarios', $this->getValidData([
            'skills' => [$skillA->id, $skillB->id],
            'profession_id' => $profession->id,
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Pepe',
            'email'=> 'emilio@email.es',
            'password' => '123456',
            'role' => 'user'
        ]);

        $user = User::findByEmail('emilio@email.es');

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/pepe',
            'user_id' => $user->id,
            'profession_id' => $profession->id
        ]);

        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillA->id,
        ]);

        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillB->id,
        ]);

        $this->assertDatabaseMissing('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillC->id,
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
    public function the_skills_must_be_an_array(){

        $this->from('usuarios/crear')
            ->post('usuarios', $this->getValidData([
                'skills' => 'PHP, JS',
            ]))->assertRedirect('usuarios/crear')
            ->assertSessionHasErrors(['skills']);

        $this->assertDatabaseEmpty('users');

    }

    /** @test */
    public function the_skills_must_be_valid(){

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->from('usuarios/crear')
            ->post('usuarios', $this->getValidData([
                'skills' => [$skillA->id, $skillB->id + 1],
            ]))->assertRedirect('usuarios/crear')
            ->assertSessionHasErrors(['skills']);

        $this->assertDatabaseEmpty('users');

    }

    /** @test */
    public function the_role_field_is_optional(){

        $this->post('usuarios', $this->getValidData([
            'role' => null
        ]))->assertRedirect('usuarios');

        $this->assertDatabaseHas('users', [
            'email' => 'emilio@email.es',
            'role' => 'user',
        ]);
    }

    /** @test */
    public function the_role_field_must_be_valid(){

        $this->post('usuarios', $this->getValidData([
            'role' => 'invalid_role'
        ]))->assertSessionHasErrors('role');

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

        return array_merge([
            'name' => 'Pepe',
            'email'=> 'emilio@email.es',
            'password' => '123456',
            'profession_id' => '',
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/pepe',
            'role' => 'user',
        ], $custom);
    }
}
