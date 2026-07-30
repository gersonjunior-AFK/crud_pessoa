<?php
 
namespace App\Http\Controllers;
 
use App\Models\Produto;
use Illuminate\Http\Request;
 
class ProdutoController extends Controller
{
    /**
     * Lista todos os produtos.
     */
    public function index()
    {
        $produtos = Produto::all();
        return response()->json($produtos);
    }
 
    /**
     * Cadastra um novo produto.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'preco' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
        ]);
 
        $produto = Produto::create($validated);
        return response()->json($produto, 201);
    }
 
    /**
     * Exclui um produto pelo ID.
     */
    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();
 
        return response()->json([
            'mensagem' => 'Produto excluído'
        ]);
    }
}
