<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    // Tela Principal: Carrega as listas
    public function index()
    {
        $aguardando = Paciente::where('status', 'Aguardando')->orderBy('id')->get();
        $observacao = Paciente::where('status', 'Em Observacao')->orderBy('id')->get();
        $finalizados = Paciente::where('status', 'Finalizado')->orderBy('id', 'desc')->take(15)->get();

        return view('sistema.index', compact('aguardando', 'observacao', 'finalizados'));
    }

    // 1. TRIAGEM (Salvar novo)
    public function store(Request $request)
    {
        // Tratamento do "Não Identificado"
        $ni = $request->has('nao_identificado') ? 1 : 0;
        
        $dados = $request->all();
        $dados['nao_identificado'] = $ni;
        // Se for NI, forçamos o nome. Se não, o Model já converte pra maiúsculo.
        if ($ni) {
            $dados['nome'] = 'PACIENTE NÃO IDENTIFICADO';
            $dados['nome_social'] = '';
        }

        // Tratamento de Selects com opção "Outros"
        if ($request->queixa === 'outra') $dados['queixa'] = "Outra: " . $request->queixa_descricao;
        if ($request->raca === 'outros') $dados['raca'] = "Outros: " . $request->raca_outros_descricao;
        if ($request->municipio === 'OUTRO') $dados['municipio'] = $request->municipio_outro;

        $dados['status'] = 'Aguardando';
        $dados['data_registro'] = now();

        Paciente::create($dados);

        return redirect()->route('sistema.index')->with('msg', '✅ Triagem salva! Paciente aguardando médico.');
    }

    // 2. ATENDIMENTO MÉDICO
    public function updateMedico(Request $request)
    {
        $paciente = Paciente::find($request->id_paciente);

        if (!$paciente) {
            return back()->with('msg', '❌ ERRO: ID não encontrado!');
        }

        // Lógica do Modal de Confirmação (Refeita para Laravel)
        // Se já está finalizado ou em observação e NÃO confirmou ainda...
        if (($paciente->status === 'Finalizado' || $paciente->status === 'Em Observacao') && !$request->has('confirmacao_reabertura')) {
            // Voltamos para a tela anterior enviando os dados para abrir o modal
            return back()->with('modal_confirmacao', [
                'dados' => $request->all(),
                'tipo' => $paciente->status === 'Finalizado' ? 'finalizado' : 'observacao'
            ]);
        }

        // Tratamento dos campos condicionais
        $dados = $request->all();
        if ($request->alergia === 'SIM') $dados['alergia_descricao'] = $request->alergia_descricao;
        if ($request->doenca_notificacao === 'SIM') $dados['doenca_descricao'] = $request->doenca_descricao;
        
        $dados['status'] = 'Em Observacao';
        
        $paciente->update($dados);

        $msg = $request->has('confirmacao_reabertura') ? "🔄 Atendimento REFEITO com sucesso!" : "⚕️ Atendimento Realizado! Paciente em OBSERVAÇÃO.";
        return redirect()->route('sistema.index')->with('msg', $msg);
    }

    // 3. FINALIZAÇÃO
    public function updateFinal(Request $request)
    {
        $paciente = Paciente::find($request->id_paciente_fim);

        if (!$paciente) return back()->with('msg', '❌ ID inválido!');
        
        // Validação: Só finaliza se tiver diagnóstico
        if (empty($paciente->diagnostico)) {
            return back()->with('msg', '⚠️ ATENÇÃO: Paciente ainda não passou pelo médico.');
        }

        // Se o destino for "TRANSFERIDO PARA", pega o detalhe, senão pega o select
        $dados = $request->all();
        
        $paciente->update([
            'destino' => $request->destino,
            'destino_detalhe' => $request->destino_detalhe,
            'observacao_desfecho' => $request->observacao_desfecho,
            'status' => 'Finalizado'
        ]);

        return redirect()->route('sistema.index')->with('msg', '🏁 Paciente FINALIZADO com sucesso!');
    }

    // EXCLUIR
    public function destroy($id)
    {
        Paciente::destroy($id);
        return redirect()->route('sistema.index')->with('msg', '🗑️ Registro excluído!');
    }

    // IMPRIMIR (Gera a view de impressão)
    public function imprimir($id)
    {
        $p = Paciente::findOrFail($id);
        // Retorna uma view separada só para impressão
        return view('sistema.imprimir', compact('p'));
    }
}