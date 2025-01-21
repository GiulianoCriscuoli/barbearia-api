<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarbeiroServicos extends Model
{
    protected $table = 'barbeiro_servicos';
    public $timestamps = false;

    protected $fillable = ['nome', 'preco', 'barbeiro_id'];

    public function servicos()
    {
        return $this->belongsTo(Barbeiro::class);
    }
}
