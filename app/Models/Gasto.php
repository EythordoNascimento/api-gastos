<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'gastos';
    protected $primaryKey = 'id';
    public $timestamps = true; 
    protected $fillable = [
        'valor',
        'data',
        'fase',
        'id_orgao'
    ];

    public function orgao()
    {
        return $this->belongsTo(OrgaoPublico::class, 'id_orgao', 'id_orgao');
    }
}
