<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    // Exibe a tela principal
    public function index()
    {
        $aguardando = Paciente::where('status', 'Aguardando')->orderBy('id')->get();
        $observacao = Paciente::where('status', 'Em Observacao')->orderBy('id')->get();
        $finalizados = Paciente::where('status', 'Finalizado')->orderBy('id', 'desc')->take(15)->get();

        return view('sistema.index', compact('aguardando', 'observacao', 'finalizados'));
    }

    // Salva a Triagem (Passo 1)
    public function store(Request $request)
    {
        // Tratamento do "Não Identificado"
        $ni = $request->has('nao_identificado') ? 1 : 0;
        
        $dados = $request->all();
        $dados['nao_identificado'] = $ni;
        $dados['nome'] = $ni ? 'PACIENTE NÃO IDENTIFICADO' : mb_strtoupper($request->nome);
        $dados['nome_social'] = $ni ? '' : $this->tratarCampo($request->nome_social);
        
        // Tratamento de campos compostos
        $dados['queixa'] = ($request->queixa === 'outra') ? "Outra: " . $request->queixa_descricao : $request->queixa;
        $dados['raca'] = ($request->raca === 'outros') ? "Outros: " . $request->raca_outros_descricao : $request->raca;
        $dados['municipio'] = ($request->municipio === 'OUTRO') ? $request->municipio_outro : $request->municipio;
        
        // Aplica o tratarCampo para os demais
        $camposTexto = ['mae', 'endereco', 'ocupacao', 'afericao', 'destino_detalhe', 'observacao_desfecho'];
        foreach($camposTexto as $campo) {
            if(isset($dados[$campo])) $dados[$campo] = $this->tratarCampo($dados[$campo]);
        }

        $dados['status'] = 'Aguardando';
        $dados['data_registro'] = now();

        Paciente::create($dados);

        return redirect()->route('sistema.index')->with('msg', '✅ Triagem salva! Paciente aguardando médico.');
    }

    // Salva o Atendimento Médico (Passo 2)
    public function updateMedico(Request $request)
    {
        $paciente = Paciente::find($request->id_paciente);

        if (!$paciente) {
            return back()->with('msg', '❌ ERRO: ID não encontrado!');
        }

        // Lógica do Modal de Confirmação
        if (($paciente->status === 'Finalizado' || $paciente->status === 'Em Observacao') && !$request->has('confirmacao_reabertura')) {
            // Retorna para a view com os dados para abrir o modal
            return back()->with('modal_confirmacao', [
                'dados' => $request->all(),
                'tipo' => $paciente->status === 'Finalizado' ? 'finalizado' : 'observacao'
            ]);
        }

        $paciente->update([
            'diagnostico' => mb_strtoupper($request->diagnostico),
            'alergia' => $request->alergia,
            'alergia_descricao' => $this->tratarCampo($request->alergia_descricao),
            'doenca_notificacao' => $request->doenca_notificacao,
            'doenca_descricao' => $this->tratarCampo($request->doenca_descricao),
            'acidente_trabalho' => $request->acidente_trabalho,
            'plano_terapeutico' => $this->tratarCampo($request->plano_terapeutico),
            'status' => 'Em Observacao'
        ]);

        $msg = $request->has('confirmacao_reabertura') ? "🔄 Atendimento REFEITO com sucesso!" : "⚕️ Atendimento Realizado! Paciente em OBSERVAÇÃO.";
        return redirect()->route('sistema.index')->with('msg', $msg);
    }

    // Salva a Finalização (Passo 3)
    public function updateFinal(Request $request)
    {
        $paciente = Paciente::find($request->id_paciente_fim);

        if (!$paciente) return back()->with('msg', '❌ ID inválido!');
        
        if (empty($paciente->diagnostico)) {
            return back()->with('msg', '⚠️ ATENÇÃO: Paciente ainda não passou pelo médico.');
        }

        $paciente->update([
            'destino' => $request->destino,
            'destino_detalhe' => $this->tratarCampo($request->destino_detalhe),
            'observacao_desfecho' => $this->tratarCampo($request->observacao_desfecho),
            'status' => 'Finalizado'
        ]);

        return redirect()->route('sistema.index')->with('msg', '🏁 Paciente FINALIZADO com sucesso!');
    }

    // Excluir
    public function destroy($id)
    {
        Paciente::destroy($id);
        return redirect()->route('sistema.index')->with('msg', '🗑️ Registro excluído!');
    }

    // Imprimir
    public function imprimir($id)
    {
        $p = Paciente::findOrFail($id);
        return view('sistema.imprimir', compact('p'));
    }

    // Função Auxiliar Privada (Substitui a do arquivo antigo)
    private function tratarCampo($valor)
    {
        $valor = trim($valor ?? '');
        if ($valor === '') return 'NÃO IDENTIFICADO';
        return mb_strtoupper($valor);
    }
}