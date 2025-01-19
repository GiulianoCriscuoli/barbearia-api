<?php

namespace App\Http\Controllers;

use App\Models\Barbeiro;
use Illuminate\Http\Request;
use App\WebServices\GeoLocalizacao;

class BarbeiroController extends Controller
{
    public function listarBarbeiros(Request $request) {

        $barbeiros = Barbeiro::all();

        if($barbeiros->isEmpty()) {

            return response()->json([
                'mensagem' => 'Não há barbeiros para listar'
            ], 204);
        }

        return response()->json([
            'mensagem' => $barbeiros
        ], 200);
    }

    public function localizaBarbeiros(Request $request) {

        $dados = $request->all();
        $endereco = $dados['cidade'];

        $geolocalizacao = new GeoLocalizacao(env('MAPS_KEY', null));
        $resultado = $geolocalizacao->localizaProfissional($endereco);

        return response()->json([
            'mensagem' => $resultado
        ], 200);

    }
}
