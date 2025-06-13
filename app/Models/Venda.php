<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    /** @use HasFactory<\Database\Factories\VendaFactory> */
    use HasFactory;

    protected $table = 'venda';
    public $timestamps = false;

    protected $fillable = [
        'nome_cliente',
        'email_cliente',
        'produto_id',
        'quantidade',
        'total',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function parcelas()
    {
        return $this->hasMany(Parcela::class, 'venda_id');
    }
}
