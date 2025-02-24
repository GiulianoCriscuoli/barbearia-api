<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarbeiroDepoimento extends Model
{
    protected $table = 'barbeiro_depoimentos';
    public $timestamps = false;

    public function barbeiro()
    {
        return $this->belongsTo(Barbeiro::class);
    }
}
