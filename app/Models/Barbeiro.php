<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barbeiro extends Model
{
    protected $table = 'barbeiros';
    public $timestamps = false;

    public function fotos() {

        return $this->hasMany(BarbeiroFotos::class);
    }

    public function servicos() {

        return $this->hasMany(BarbeiroServicos::class);
    }

    public function depoimentos() {

        return $this->hasMany(BarbeiroDepoimento::class);
    }

    public function disponibilidades()
    {
        return $this->hasMany(BarbeiroDisponibilidade::class);
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
