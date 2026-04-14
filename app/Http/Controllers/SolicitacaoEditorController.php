<?php

namespace App\Http\Controllers;

use App\Models\SolicitacaoEditor;
use Illuminate\Http\Request;

class SolicitacaoEditorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'motivo'      => 'required|string|min:20|max:500',
            'experiencia' => 'nullable|string|max:300',
        ]);

        $jaTemSolicitacao = SolicitacaoEditor::where('user_id', auth()->id())
            ->where('status', 'pendente')
            ->exists();

        if ($jaTemSolicitacao) {
            return back()->with('erro_solicitacao', 'Você já tem uma solicitação pendente!');
        }

        SolicitacaoEditor::create([
            'user_id'     => auth()->id(),
            'motivo'      => $request->motivo,
            'experiencia' => $request->experiencia,
            'status'      => 'pendente',
        ]);

        return back()->with('success_solicitacao', 'Solicitação enviada! Aguarde a aprovação. ⏳');
    }

    public function aprovar($id)
    {
        if (auth()->user()->tipo !== 'administrador') abort(403);
        $solicitacao = SolicitacaoEditor::findOrFail($id);
        $solicitacao->update(['status' => 'aprovado']);
        $solicitacao->user->update(['tipo' => 'editor']);
        return back()->with('success', "✅ {$solicitacao->user->nome} agora é Editor!");
    }

    public function rejeitar($id)
    {
        if (auth()->user()->tipo !== 'administrador') abort(403);
        $solicitacao = SolicitacaoEditor::findOrFail($id);
        $solicitacao->update(['status' => 'rejeitado']);
        return back()->with('success', "❌ Solicitação de {$solicitacao->user->nome} rejeitada.");
    }

    public function index()
    {
        if (auth()->user()->tipo !== 'administrador') abort(403);

        $solicitacoes = SolicitacaoEditor::with('user')
            ->where('status', 'pendente')
            ->latest()
            ->get();

        $editores = \App\Models\User::where('tipo', 'editor')->get();

        return view('admin.solicitacoes', compact('solicitacoes', 'editores'));
    }

    public function removerEditor($id)
{
    if (auth()->user()->tipo !== 'administrador') abort(403);

    $user = \App\Models\User::findOrFail($id);
    $user->update(['tipo' => 'aluno']);

    return back()->with('success', "🔄 {$user->nome} removido dos editores.");
}
}
