<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DespesaPE extends Model
{
    protected $table = 'despesas_pe';
    protected $primaryKey = 'id_despesa';
    public $timestamps = false;

    protected $fillable = [
        'finalidade',
        'credor',
        'situacao',
        'ordem_bancaria',
        'unidade_gestora',
        'data',
        'valor',
        'numero_empenho'
    ];
}
