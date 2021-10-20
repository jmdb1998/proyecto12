<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){

        if(request()->has('empty')){
            $users = [];
        }else{
            $users = ['Joel','Ellie','Tess','Tommy','Bill'];
        }

        $title = 'Usuarios';

        return view('users.index', compact('users','title'));
        //return view('users')->with(compact('users','title')); Es otra forma de pasarle variables a una vista
    }

    public function create()
    {
        return 'Creando un nuevo usuario';
    }

    public function show($id)
    {
        return view('users.show', compact('id'));
    }
}
