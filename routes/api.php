<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\BarbeiroController;

Route::get('401', [AutenticacaoController::class, 'naoAutorizado'])->name('login');
Route::post('cadastrar', [AutenticacaoController::class, 'cadastrar']);
Route::post('logar', [AutenticacaoController::class, 'logar']);
Route::post('sair', [AutenticacaoController::class, 'sair']);
Route::post('atualizar/login', [AutenticacaoController::class, 'atualizarLogin']);
Route::post('atualiza-token', [AutenticacaoController::class, 'atualizaToken']);

Route::get('usuario', [UsuarioController::class, 'lerUsuario']);
Route::put('usuario/{id}', [UsuarioController::class, 'atualizarUsuario']);
Route::get('usuario/favoritos', [UsuarioController::class, 'listarFavoritos']);
Route::post('usuario/favorito', [UsuarioController::class, 'adicionarFavoritos']);
Route::get('usuario/agendamentos', [UsuarioController::class, 'agendamentos']);


Route::get('barbeiros', [BarbeiroController::class, 'listarBarbeiros']);
Route::get('localiza-babeiros', [BarbeiroController::class, 'localizaBarbeiros']);
Route::get('barbeiro/{id}', [BarbeiroController::class, 'acessarBarbeiro']);
Route::post('barbeiro/{id}/agendamento', [BarbeiroController::class, 'criarAgendamento']);

Route::get('pesquisar', [BarbeiroController::class, 'pesquisarBarbeiro']);
