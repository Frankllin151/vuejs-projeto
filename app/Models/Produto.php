<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    /** @use HasFactory<\Database\Factories\ProdutoFactory> */
    use HasFactory;

    protected $table = 'produto';

    protected $fillable = [
        'nome',
        'preco',
    ];

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'produto_id');
    }
}
