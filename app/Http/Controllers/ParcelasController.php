<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parcela;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ParcelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendasall = Venda::all();
        $vendas = Venda::with(['parcelas' => function ($query) {
            $query->orderBy('numero_parcela');
        }])->get();

        $dadosFormatados = $vendas->map(function ($venda) {
            return [
                'venda_id' => $venda->id,
                'venda' => [
                   'id' => $venda->id,
                   'nome_cliente' => $venda->nome_cliente,
                   'email_cliente' =>  $venda->email_cliente,
                   'quantidade' => $venda->quantidade,
                   'produto_id' => $venda->produto_id,
                   'total' => $venda->total,
                ],
                'numero_parcela' => $venda->parcelas->count(),
                'data_vencimento' => $venda->parcelas->pluck('data_vencimento')->toArray(),
                'valor' => $venda->parcelas->pluck('valor')->toArray(),

            ];
        });

     return Inertia::render('Parcela', [
           "dados" => $dadosFormatados, 
           "vendasAll" => $vendasall
          ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $parcelas = $request->all(); // array de parcelas

    foreach ($parcelas as $parcela) {
        Parcela::create([
            'venda_id'        => $parcela['venda_id'],
            'numero_parcela'  => $parcela['numero_parcela'],
            'data_vencimento' => $parcela['data_vencimento'],
            'valor'           => $parcela['valor'],
        ]);
    }
     return Inertia::location(route('parcela.all'));
    }

   


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request  $request ,string $id)
    {
       
    $dados = $request->all();
    Parcela::where('venda_id', $id)->delete();
    
    $novaLista = [];

    foreach ($dados['valor'] as $index => $valor) {
        $novaLista[] = [
            'venda_id' => $id,
            'numero_parcela' => $index + 1,
            'data_vencimento' => $dados['data_vencimento'][$index] ?? null,
            'valor' => $valor,
        ];
    }

    
    Parcela::insert($novaLista);

     return Inertia::location(route('parcela.all'));
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
    Parcela::where('venda_id', $id)->delete();
    Venda::where('id', $id)->delete();
    return Inertia::location(route('parcela.all'));
    }
}
