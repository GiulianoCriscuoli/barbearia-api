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

}
