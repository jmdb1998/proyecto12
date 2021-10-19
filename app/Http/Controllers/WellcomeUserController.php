<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WellcomeUserController extends Controller
{
    public function __invoke($name, $nickname = null)
    {
        return $nickname
            ? 'Bienvenido ' . ucfirst($name) . ' tu apodo es ' . $nickname
            : 'Hola ' . ucfirst($name);
    }
}
