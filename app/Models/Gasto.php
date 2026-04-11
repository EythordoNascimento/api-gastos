<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'despesa';
    protected $primaryKey = 'id_despesa';
    public $timestamps = false;

    // Campos permitidos para mass assignment (ajustados para sua tabela)
    protected $fillable = [
        'tipo',          // usado como descrição
        'valor',
        'data',
        'fase',
        'id_orgao'       // FK para orgao_publico
    ];

    // Relacionamento com órgão público
    public function orgao()
    {
        return $this->belongsTo(OrgaoPublico::class, 'id_orgao', 'id_orgao');
    }
}