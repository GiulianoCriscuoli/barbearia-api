<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarbeiroDisponibilidade extends Model
{
    protected $table = 'barbeiro_disponibilidades';
    public $timestamps = false;
    protected $fillable = ['weekday', 'hours', 'barbeiro_id'];

    public function barbeiro()
    {
        return $this->belongsTo(Barbeiro::class);
    }

}
