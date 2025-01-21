<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarbeiroFotos extends Model
{
    protected $table = 'barbeiro_fotos';
    public $timestamps = false;

    protected $fillable = ['url', 'nome_arquivo', 'barbeiro_id'];

    public function barbeiro()
    {
        return $this->belongsTo(Barbeiro::class);
    }
}
