<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    /**
     * Lista todos os alunos.
     */
    public function index()
    {
        // Busca todos os registros da tabela "alunos"
        $alunos = Aluno::all();

        // Retorna os alunos em formato JSON
        return response()->json($alunos);
    }

    /**
     * Cadastra um novo aluno.
     */
    public function store(Request $request)
    {
        // Valida os dados recebidos pelo formulário
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:alunos,email',
            'curso' => 'required|string|max:100',
        ]);

        // Cria o aluno no banco de dados
        $aluno = Aluno::create($validated);

        // Retorna o aluno criado em JSON
        return response()->json($aluno, 201);
    }

    /**
     * Exclui um aluno pelo ID.
     */
    public function destroy($id)
    {
        // Busca o aluno pelo ID ou retorna erro 404
        $aluno = Aluno::findOrFail($id);

        // Exclui o registro encontrado
        $aluno->delete();

        // Retorna confirmação de exclusão
        return response()->json([
            'mensagem' => 'Aluno excluído',
        ]);
    }
}
