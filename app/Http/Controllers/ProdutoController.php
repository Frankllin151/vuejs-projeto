<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use Inertia\Inertia;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Produto::all();
         return Inertia::render('Produto', [
            "data" => $data
          ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
       $request->validate([
        'nome' => 'required|string|max:255',
        'preco' => 'required|numeric|min:0',
        ]);
       Produto::create([
        'nome' => $request->nome,
        'preco' => $request->preco,
       ]);
        
       return Inertia::location(route('produto.index'));
    }

    
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        //
         $validar= $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
        ]);
        $produto = Produto::findOrFail($id); 
        $produto->update($validar);

       return Inertia::location(route('produto.index'));

        
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    $produto = Produto::findOrFail($id);
    $produto->delete();
    return Inertia::location(route('produto.index'));
    }
}
