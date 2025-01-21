<?php

namespace App\WebServices;

use Illuminate\Support\Facades\Http;
use Exception;

class GeoLocalizacao {
    private string $chave;
    protected string $url;

    public function __construct(string $chave = null) {
        $this->chave = $chave ?? env('MAPS_KEY', null);
    }

    public function  localizaProfissional($endereco) {

        $this->url = "https://maps.googleapis.com/maps/api/geocode/json?address=$endereco&key=$this->chave";

        try {
            $resposta = Http::get($this->url, [
                'address' => $endereco,
                'key' => $this->chave
            ]);

            $dado = $resposta->json();


            if ($resposta->successful() && $dado['status'] === 'OK') {
                $resultado = $dado['results'][0];

                return [
                    'latitude' => $resultado['geometry']['location']['lat'],
                    'longitude' => $resultado['geometry']['location']['lng'],
                    'endereco' => $resultado['formatted_address'],
                    'resposta' => $resultado
                ];
            }

            // throw new Exception('Geocoding falhou: ' . ($data['status'] ?? 'Unknown error'));
        } catch (Exception $e) {
            throw new Exception('Geocoding erro: ' . $e->getMessage());
        }

    }
}

