<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Profession;
use App\Skill;
use App\User;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){

        $users = User::all();

        $title = 'Usuarios';

        return view('users.index', compact('users','title'));
        //return view('users')->with(compact('users','title')); Es otra forma de pasarle variables a una vista

        /*return view('users.index')
            ->with('users', User::all())      Otra forma de hacer lo de arriba
            ->with('title', 'Listado de Usuarios');*/
    }

    public function create()
    {
        $professions = Profession::orderBy('title','ASC')->get();

        $skills = Skill::orderBy('name', 'ASC')->get();

        $roles = trans('users.roles');

        $user = new User();

        return view('users.create', compact('professions', 'skills', 'roles','user'));

    }

    public function store(CreateUserRequest $request)
    {

        $request->createUser();
        //User::createUser($request->validated());  Otra forma de hacerlo

        return redirect()->route('users.index');

    }

    public function show(User $user)
    {
        if ($user == null)
        {
            return response()->view('errors.404', [], 404);
        }
        return view('users.show', compact('user'));
    }

    public function edit(User $user){

        $professions = Profession::orderBy('title','ASC')->get();

        $skills = Skill::orderBy('name', 'ASC')->get();

        $roles = trans('users.roles');

        return view('users.edit', compact('user', 'professions', 'skills', 'roles'));
    }

    public function update(User $user){
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. $user->id,
            'password' => '',
        ]);

        if($data['password'] != null){
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.show', $user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
