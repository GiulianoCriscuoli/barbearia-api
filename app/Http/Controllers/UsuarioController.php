<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    private $usuarioLogado;

    public function __construct() {
        $this->usuarioLogado = Auth::user();
    }

    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:api')
        ];
    }

    public function lerUsuario() {

        $usuario = $this->usuarioLogado;

        return response()->json([
            'data' => $usuario
        ], 200);
    }
}
