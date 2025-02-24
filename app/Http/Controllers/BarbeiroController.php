<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
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
                'mensagem' => 'Barbeiro não encontrado'
            ], 204);
        }

        $fotosBarbeiro = $barbeiro->fotos()->select('url')->get();
        $servicosBarbeiro = $barbeiro->servicos()->select(['preco', 'nome'])->get();
        $depoimentosBarbeiro = $barbeiro->depoimentos()->select(['avaliacao', 'nome', 'depoimento'])->get();
        $disponibilidadeBarbeiro = $barbeiro->disponibilidades()->select(['weekday', 'hours'])->get();

        $horariosDisponiveisPorDia = [];

        foreach($disponibilidadeBarbeiro as $disp) {
            $horariosDisponiveisPorDia[$disp['weekday']] = explode(',', $disp['hours']);
        }

        $agendamentos = [];

        $agendamentosUsuario = $barbeiro->agendamentos()
        ->whereBetween('data_agendamento', [
            date('Y-m-d').' 00:00:00',
            date('Y-m-d', strtotime('+30 days')). ' 23:59:59'
        ])->get();

            foreach($agendamentosUsuario as $agendamento) {
                $agendamentos = $agendamento['data_agendamento'];
            }

            if (!is_array($agendamentos)) {
                $agendamentos = [$agendamentos];
            }

            $disponiveis = [];

            for ($dias = 0; $dias <= 30; $dias++) {
                $tempoEmdias = strtotime('+' . $dias . ' days');
                $diaDaSemana = date('w', $tempoEmdias);

                if (isset($horariosDisponiveisPorDia[$diaDaSemana])) {
                    $horas = [];
                    $dia = date('Y-m-d', $tempoEmdias);

                    foreach ($horariosDisponiveisPorDia[$diaDaSemana] as $disponivel) {
                        $diaFormatado = $dia . ' ' . $disponivel . ':00';

                        if (!in_array($diaFormatado, $agendamentos)) {
                            $horas[] = $disponivel;
                        }
                    }

                    if (count($horas) > 0) {
                        $disponiveis[] = [
                            'data' => $dia,
                            'horas' => $horas
                        ];
                    }
                }
            }

        return response()->json([
            'mensagem' => [
                $barbeiro,
                $fotosBarbeiro,
                $servicosBarbeiro,
                $depoimentosBarbeiro,
                $agendamentos,
                $disponiveis
            ]
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
