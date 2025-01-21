<?php

namespace App\Http\Controllers;

use App\Models\Barbeiro;
use Illuminate\Http\Request;
use App\WebServices\GeoLocalizacao;
use Illuminate\Support\Facades\DB;

class BarbeiroController extends Controller
{
    public function listarBarbeiros(Request $request) {

        $offset = !empty($request->input('offset')) ? $request->input('offset') : 0;

        $barbeiros = Barbeiro::orderBy('nome', 'ASC')->offset($offset)->limit(5)->get();

        if($barbeiros->isEmpty()) {
            return response()->json([
                'mensagem' => 'Não há barbeiros para listar'
            ], 204);
        }

        return response()->json([
            'mensagem' => $barbeiros
        ], 200);
    }

    public function acessarBarbeiro($id) {

        $barbeiro = Barbeiro::where('id', $id)->firstOrFail();

        if(!$id) {
            return response()->json([
                'mensagem' => 'Batbeiro não encontrado'
            ], 204);
        }

        $fotosBarbeiro = $barbeiro->fotos()->select('url')->get();
        $servicosBarbeiro = $barbeiro->servicos()->select(['preco', 'nome'])->get();

        return response()->json([
            'mensagem' => [$barbeiro, $fotosBarbeiro, $servicosBarbeiro]
        ], 200);
    }

    // public function localizaBarbeiros(Request $request) {
    //     $dados = $request->all();
    //     $endereco = isset($dados['cidade']) ? $dados['cidade'] : $dados['latitude'].",".$dados['longitude'];

    //     if(!empty($dados)) {
    //         return response()->json([
    //             'mensagem' => 'Nenhum resultado encontrado. Informe a cidade ou latitude e longitude do barbeiro desejado'
    //         ], 200);

    //     }

    //     $distancia = 10;

    //     $barbeiros = Barbeiro::select(DB::raw('*,
    //     SQRT(
    //         POW(69.1 * (latitude - ' . $dados['latitude'] . '), 2) +
    //         POW(69.1 * (' . $dados['longitude'] . ' - longitude), 2)
    //     ) AS distance'))
    //     ->having('distance', '<=', $distancia)
    //     ->orderBy('distance')
    //     ->get();


    //     $geolocalizacao = new GeoLocalizacao(env('MAPS_KEY', null));
    //     $resultado = $geolocalizacao->localizaProfissional($endereco);

    //     return response()->json([
    //         'mensagem' => [$resultado, $barbeiros]
    //     ], 200);

    // }
}
