<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $fillable = ['usuario_id', 'data_agendamento', 'barbeiro_id'];

    public function barbeiro()
    {
        return $this->belongsTo(Barbeiro::class);
    }
}
