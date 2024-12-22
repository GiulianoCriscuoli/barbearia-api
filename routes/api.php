<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\BarbeiroController;

Route::post('login', [AutenticacaoController::class, 'login']);
Route::post('sair', [AutenticacaoController::class, 'sair']);
Route::post('atualizar/login', [AutenticacaoController::class, 'atualizarLogin']);
Route::post('cadastrar', [AutenticacaoController::class, 'cadastrarLogin']);

Route::get('usuario', [UsuarioController::class, 'lerUsuario']);
Route::put('usuario/{id}', [UsuarioController::class, 'atualizarUsuario']);
Route::get('usuario/favoritos', [UsuarioController::class, 'listarFavoritos']);
Route::post('usuario/favorito', [UsuarioController::class, 'adicionarFavoritos']);
Route::get('usuario/agendamentos', [UsuarioController::class, 'agendamentos']);


Route::get('babeiros', [BarbeiroController::class, 'listarBarbeiros']);
Route::get('babeiro/{id}', [BarbeiroController::class, 'acessarBarbeiro']);
Route::post('babeiro/{id}/agendamento', [BarbeiroController::class, 'criarAgendamento']);

Route::get('pesquisar', [BarbeiroController::class, 'pesquisarBarbeiro']);
