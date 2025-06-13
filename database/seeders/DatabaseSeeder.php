<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Parcela;
use App\Models\Produto;
use App\Models\Venda;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Produto::factory(10)->create();

    Venda::factory(20)->create()->each(function ($venda) {
        $total = $venda->total;

        $entrada = fake()->randomFloat(2, 10, $total * 0.5);
        $restante = $total - $entrada;
        $numParcelas = fake()->numberBetween(2, 12);
        $valorParcela = round($restante / $numParcelas, 2);

        // Primeira parcela (entrada)
        Parcela::create([
            'venda_id' => $venda->id,
            'numero_parcela' => 1,
            'data_vencimento' => now(),
            'valor' => $entrada,
        ]);

        // Demais parcelas
        for ($i = 2; $i <= $numParcelas + 1; $i++) {
            Parcela::create([
                'venda_id' => $venda->id,
                'numero_parcela' => $i,
                'data_vencimento' => now()->addMonths($i - 1),
                'valor' => $valorParcela,
            ]);
        }
    });
    }
}
