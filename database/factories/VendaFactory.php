<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produto;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venda>
 */
class VendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       $produto = Produto::inRandomOrder()->first() ?? Produto::factory()->create();
    $quantidade = $this->faker->numberBetween(1, 10);
    $total = $quantidade * $produto->preco;

    return [
        'nome_cliente' => $this->faker->name(),
        'email_cliente' => $this->faker->safeEmail(),
        'produto_id' => $produto->id,
        'quantidade' => $quantidade,
        'total' => $total,
    ];
    }
}
