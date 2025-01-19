<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AutenticacaoController extends Controller
{
    public function __construct() {
    }

    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:api', except: ['cadastrar', 'login','atualizaToken','naoAutorizado']),
        ];
    }

    public function cadastrar(Request $request) {

        $resposta = [];

        try {
            // Inicia a transação
            DB::beginTransaction();

            // Validando os dados da request
            $validator = Validator::make($request->all(), [
                'nome' => 'required',
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $nome  = $request->nome;
            $email = $request->email;
            $senha = $request->password;

            // Verifica se falhou
            if ($validator->fails()) {
                return response()->json(['error' => 'Dados não coincidem ou não preenchidos'], 400);
            }

            // Consulta se o email já existe na base
            $emailExistente = User::where('email', $email)->first();

            if ($emailExistente) {
                return response()->json(['error' => 'Este email já está cadastrado'], 400);
            }

            $usuario = new User();
            $usuario->nome =  $nome;
            $usuario->email = $email;
            $usuario->password = Hash::make($senha);
            $usuario->save();

            // Confirma a transação no banco
            DB::commit();

            // Tenta logar
            $token = JWTAuth::attempt([
                'email' => $email,
                'password' => $senha
            ]);

            if (!$token) {

                return response()->json(['error' => 'Erro ao autenticar usuário'], 500);
            }

            $usuarioLogado = Auth::user();
            $usuarioLogado['avatar'] = url('media/avatar/' . $usuarioLogado['avatar']);
            $resposta = ['data' => $usuarioLogado];

            return response()->json($resposta, 201);

        } catch (\Exception $exception) {
            // Reverte a transação em caso de erro

            DB::rollBack();

            return response()->json(['error' => 'Uma exceção ocorreu: ' . $exception->getMessage()], 500);
        }
    }

    public function logar(Request $request) {

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            ];

        $token = JWTAuth::attempt($credentials);

        if(!$token) {
            return response()->json(data: ['error' => 'Email ou senha incorretos'], status: 400);
        }

        $usuario = Auth::user();
        $usuario['token'] = $token;

        return response()->json([
            $usuario,
            'mensagem' => 'Usuário logado'
        ], status: 200);

    }

    public function sair() {

        $token = JWTAuth::getToken();

        if(!$token) {
            return response()->json(data: ['error' => 'Usuário não encontrado'], status: 400);
        }

        $usuario = JWTAuth::parseToken()->authenticate();
        Auth::logout();

        return response()->json([
            'mensagem' => "Usuário $usuario->nome está deslogado"
        ], status: 200);
    }

    public function atualizaToken() {

        try {
            $usuarioLogado = Auth::user();

            if (!$usuarioLogado) {
                return response()->json(['mensagem' => 'Usuário não está logado'], 401);
            }

            // Verifica se existe um token
            if (!$token = JWTAuth::getToken()) {
                return response()->json(['mensagem' => 'Token não encontrado'], 401);
            }

            // Tenta atualizar o token
            try {
                $newToken = JWTAuth::refresh($token);

            } catch (TokenExpiredException $e) {
                // Se o token expirou há muito tempo, force um novo login
                return response()->json(['mensagem' => 'Token expirado, faça login novamente'], 401);
            }

            // Retorna o novo token
            return response()->json([
                'token' => $newToken,
                'usuario' => $usuarioLogado
            ], 200);

        } catch (JWTException $e) {
            return response()->json(['mensagem' => 'Não foi possível atualizar o token'], 500);
        }
    }

    public function naoAutorizado() {
        return response()->json(['mensagem' => 'Não autorizado o acesso'], 401);
    }

}
