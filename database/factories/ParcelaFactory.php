<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Venda;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Parcela>
 */
class ParcelaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

     $venda = Venda::inRandomOrder()->first() ?? Venda::factory()->create();

    $total = $venda->total;

    // Valor da primeira parcela
    $valor_entrada = $this->faker->randomFloat(2, 10, $total * 0.5);
    $valor_restante = $total - $valor_entrada;

    $numero_parcelas = $this->faker->randomElement([2, 3, 4, 6, 8, 12]);
    $valor_parcela = $valor_restante / $numero_parcelas;

    $parcelas = [];

    // Primeira parcela (entrada)
    $parcelas[] = [
        'venda_id' => $venda->id,
        'numero_parcela' => 1,
        'data_vencimento' => now(),
        'valor' => $valor_entrada,
    ];

    // Demais parcelas
    for ($i = 2; $i <= $numero_parcelas + 1; $i++) {
        $parcelas[] = [
            'venda_id' => $venda->id,
            'numero_parcela' => $i,
            'data_vencimento' => now()->addMonths($i - 1),
            'valor' => round($valor_parcela, 2),
        ];
    }

    // Retorna uma parcela aleatória só pra factory gerar corretamente (você pode usar seeder para gerar todas)
    return $this->faker->randomElement($parcelas);
    }
}
