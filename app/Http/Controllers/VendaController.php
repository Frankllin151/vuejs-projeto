<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use App\Models\Venda;
use Inertia\Inertia;

class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $data = Venda::with('produto')->orderBy('id', 'desc')->get();
         $produtoAll = Produto::all();
    
      
        return Inertia::render('Dashboard', [
            "data" => $data,
            "produtoall" => $produtoAll
          ]);  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
 $request->validate([
        'nome_cliente' => 'required|string|max:255',
        'email_cliente' => 'required|email|max:255',
        'quantidade' => 'required|integer',
        'produto_id' => 'required|exists:produto,id',
        'total' => 'required|numeric|min:0',
    ]);
    Venda::create([
         'nome_cliente' => $request->nome_cliente,
        'email_cliente' => $request->email_cliente,
        'quantidade' => $request->quantidade,
        'produto_id' => $request->produto_id,
        'total' => $request->total
    ]);

     return Inertia::location(route('dashboard'));
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
         $venda = Venda::findOrFail($id);
         if($venda->parcelas()->exists()){
             $venda->parcelas()->delete();
         }
         $venda->delete();
      return Inertia::location(route('dashboard'));     
    }
}
