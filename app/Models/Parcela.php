<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    /** @use HasFactory<\Database\Factories\ParcelaFactory> */
    use HasFactory;

    protected $table = 'parcelas';
    public $timestamps = false;

    protected $fillable = [
        'venda_id',
        'numero_parcela',
        'data_vencimento',
        'valor',
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }
}
