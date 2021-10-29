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
}
