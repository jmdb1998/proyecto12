<?php

namespace App\Http\Controllers;

use App\User;
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
        return view('users.create');
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ],[
            'name.required' => 'El campo nombre es obligatorio',
            'email.required' => 'El campo email es obligatorio',
            'password.required' => 'El campo password es obligatorio',
            'email.unique' => 'Ese email ya existe'
        ]);


        User::create([
            'name' => $data['name'],
            'email'=> $data['email'],
            'password' => bcrypt($data['password'])
        ]);

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
        return view('users.edit', compact('user'));
    }

    public function update(User $user){
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
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
}
