<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgaoPublico extends Model
{
    use HasFactory;

    protected $table = 'orgao_publico';
    protected $primaryKey = 'id_orgao';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'esfera',
        'sigla',
        'codigo'
    ];
}