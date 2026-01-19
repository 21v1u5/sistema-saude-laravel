<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SEMUS - Sistema de Atendimento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* SEU CSS ORIGINAL MANTIDO INTEGRALMENTE */
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #E5E9F2; margin: 0; padding: 0; color: #444; }
        header { background: #1351B4; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 30px; }
        header img { height: 50px; background: white; padding: 5px; border-radius: 8px; margin-bottom: 10px; }
        header strong { font-size: 18px; text-transform: uppercase; display: block; }
        .main-container { max-width: 1100px; margin: 0 auto; padding: 0 20px 40px 20px; }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); padding: 25px; margin-bottom: 25px; border: 1px solid #e1e5eb; border-top: 5px solid #1351B4; }
        .card-triagem { border-top: 5px solid #00aaff; }
        .card-triagem h3 { color: #00aaff; border-bottom-color: #00aaff; }
        .card-observacao { border-top: 5px solid #ffc107; }
        .card-observacao h3 { color: #856404; border-bottom-color: #ffc107; }
        .card-finalizados { border-top: 5px solid #28a745; }
        .card-finalizados h3 { color: #28a745; border-bottom-color: #28a745; }
        h2, h3 { color: #1351B4; margin: 0 0 20px 0; font-size: 16px; text-transform: uppercase; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 700; font-size: 12px; color: #555; text-transform: uppercase; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; box-sizing: border-box; font-size: 14px; background: #f8f9fa; transition: 0.3s; }
        input:focus, select:focus, textarea:focus { border-color: #1351B4; background: #fff; outline: none; }
        .hidden { display: none; }
        .btn { padding: 14px; border: none; border-radius: 6px; color: white; cursor: pointer; font-weight: bold; width: 100%; margin-top: 10px; font-size: 14px; transition: 0.2s; background: #1351B4; }
        .btn:hover { background: #0f4496; }
        .btn-acao { text-decoration: none; font-size: 16px; margin: 0 5px; }
        .msg { padding: 15px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f1f3f5; text-align: left; padding: 12px; font-size: 12px; color: #555; font-weight: 700; border-bottom: 2px solid #ddd; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 13px; color: #555; }
        tr:hover td { background: #f9f9f9; }
        .check-wrapper { display: flex; align-items: center; gap: 8px; background: #f8f9fa; padding: 10px; border-radius: 6px; border: 1px solid #ddd; margin-bottom: 15px; cursor: pointer; }
        .check-wrapper input { width: auto; margin: 0; }
        .check-wrapper label { margin: 0; cursor: pointer; }
        .badge { padding: 5px 10px; border-radius: 12px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-success { background-color: #28a745; color: white; }
        
        /* MODAL */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: #fff; padding: 30px; border-radius: 10px; width: 90%; max-width: 500px; text-align: center; border-top: 5px solid #ffc107; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .modal-content h3 { color: #e67e22; border-bottom: none; font-size: 20px; }
        .modal-content p { font-size: 15px; color: #555; margin-bottom: 25px; }
        .modal-actions { display: flex; gap: 10px; justify-content: center; }
        .btn-sim { background: #28a745; color: white; border: none; padding: 12px 20px; border-radius: 5px; font-weight: bold; cursor: pointer; text-decoration: none; flex: 1; }
        .btn-nao { background: #dc3545; color: white; border: none; padding: 12px 20px; border-radius: 5px; font-weight: bold; cursor: pointer; text-decoration: none; flex: 1; }
    </style>
</head>
<body>

{{-- MODAL DE CONFIRMAÇÃO (LÓGICA LARAVEL/BLADE) --}}
@if(session('modal_confirmacao'))
    @php $modal = session('modal_confirmacao'); @endphp
    <div class="modal-overlay">
        <div class="modal-content">
            @if ($modal['tipo'] === 'finalizado')
                <h3>⚠️ Paciente já Finalizado!</h3>
                <p>Tem certeza que deseja que esse paciente volte para a observação?</p>
            @else
                <h3>⚠️ Atenção: Refazer Atendimento</h3>
                <p>Este paciente já está em <strong>Observação</strong>. <br>Tem certeza que deseja refazer o Atendimento Médico?</p>
            @endif
            
            <div class="modal-actions">
                <form method="post" action="{{ route('pacientes.medico') }}" style="flex:1; margin:0;">
                    @csrf
                    {{-- Reenvia os dados originais --}}
                    @foreach ($modal['dados'] as $chave => $valor)
                        <input type="hidden" name="{{ $chave }}" value="{{ $valor }}">
                    @endforeach
                    <input type="hidden" name="confirmacao_reabertura" value="1">
                    <button type="submit" class="btn-sim">SIM, CONTINUAR</button>
                </form>
                <a href="{{ route('sistema.index') }}" class="btn-nao">NÃO</a>
            </div>
        </div>
    </div>
@endif

<header>
    {{-- Certifique-se que a imagem está em public/logo_sao_luis.png --}}
    <img src="{{ asset('logo_sao_luis.png') }}"><br>
    <strong>PREFEITURA DE SÃO LUÍS – MA</strong>
    Secretaria Municipal de Saúde – SEMUS

    <div style="margin-top: 15px;">
        <a href="{{ route('dashboard') }}" class="btn" style="background: #e67e22; width: auto; display: inline-block; margin: 0 5px; text-decoration: none;">📊 Dashboard</a>
        <a href="{{ route('relatorios.index') }}" class="btn" style="background: #27ae60; width: auto; display: inline-block; margin: 0 5px; text-decoration: none;">📄 Relatórios</a>
    </div>
</header>

<div class="main-container">
    
    @if(session('msg'))
        <div class="msg" style="background: {{ str_contains(session('msg'), '✅') || str_contains(session('msg'), '⚕️') ? '#d4edda' : '#f8d7da' }}; color: {{ str_contains(session('msg'), '✅') || str_contains(session('msg'), '⚕️') ? '#155724' : '#721c24' }}">
            {{ session('msg') }}
        </div>
    @endif

    {{-- 1. TRIAGEM --}}
    <form method="post" action="{{ route('pacientes.store') }}" class="card">
        @csrf
        <h2>1. Identificação e Triagem</h2>
        
        <div class="check-wrapper">
            <input type="checkbox" id="ni" name="nao_identificado" value="1">
            <label for="ni">PACIENTE NÃO IDENTIFICADO</label>
        </div>

        <div class="grid">
            <div><label>Nome Civil*:</label><input name="nome" id="fn" required></div>
            <div><label>Nome Social:</label><input name="nome_social" id="fs"></div>
        </div>
        <div class="grid">
            <div><label>Nome da Mãe:</label><input name="mae" id="fm"></div>
            <div><label>CPF:</label><input name="cpf" id="fc"></div>
        </div>
        <div class="grid" style="grid-template-columns: 1fr 1fr 1fr;">
            <div><label>Data Nascimento:</label><input type="date" name="data_nascimento" id="fd"></div>
            <div><label>Idade:</label><input name="idade" id="fi" readonly style="background:#e9ecef;"></div>
            <div><label>Sexo:</label>
                <select name="sexo">
                    <option value="" style="color:#888;">SELECIONE...</option>
                    <option value="MASCULINO">MASCULINO</option>
                    <option value="FEMININO">FEMININO</option>
                </select>
            </div>
        </div>
        <div class="grid">
            <div><label>Escolaridade:</label>
                <select name="escolaridade">
                    <option value="" style="color:#888;">SELECIONE...</option>
                    <option value="Ensino Fundamental">Ensino Fundamental</option>
                    <option value="Médio">Ensino Médio</option>
                    <option value="Superior">Ensino Superior</option>
                    <option value="IGNORADO">IGNORADO</option>
                </select>
            </div>
            <div><label>Raça/Cor:</label>
                <select name="raca" id="raca-select">
                    <option value="" style="color:#888;">SELECIONE...</option>
                    <option value="Branco">Branco</option>
                    <option value="Preto">Preto</option>
                    <option value="Pardo">Pardo</option>
                    <option value="Amarelo">Amarelo</option>
                    <option value="Indígena">Indígena</option>
                    <option value="outros">Outros</option>
                </select>
                <div id="box_raca_outros" class="hidden" style="margin-top:5px;"><input name="raca_outros_descricao" placeholder="Qual raça?"></div>
            </div>
        </div>
        <div class="grid" style="grid-template-columns: 1fr 1fr 1fr;">
            <div><label>Ocupação:</label><input name="ocupacao" placeholder="Profissão"></div>
            <div><label>UF:</label><input name="uf" value="MA" maxlength="2"></div>
            <div><label>CEP:</label><input name="cep"></div>
        </div>
        <div class="grid">
            <div style="flex: 2;"><label>Endereço:</label><input name="endereco"></div>
            <div><label>Telefone:</label><input name="telefone"></div>
        </div>
        <div class="grid">
            <div><label>Município:</label>
                <select name="municipio" id="mun">
                    <option value="" style="color:#888;">SELECIONE...</option>
                    <option value="São Luís">São Luís</option>
                    <option value="OUTRO">Outros</option>
                </select>
                <div id="b_mun" style="display:none; margin-top:5px;"><input name="municipio_outro" placeholder="Qual cidade?"></div>
            </div>
            <div><label>Hora Entrada*:</label><input type="time" name="horario_entrada" required></div>
        </div>
        
        <div style="margin-top: 15px;">
            <label style="background: #e9ecef; padding: 8px; border-left: 4px solid #1351B4; margin-bottom: 10px;">AFERIÇÃO MÉDICA (Sinais Vitais, PA, Temp, etc):</label>
            <textarea name="afericao" rows="3" placeholder="Digite aqui a PA, Temperatura, Peso, Saturação..."></textarea>
        </div>

        <div class="grid" style="margin-top: 15px;">
            <div style="flex: 1;"><label>Queixa Principal*:</label>
                <select id="queixa-principal" name="queixa" required>
                    <option value="" disabled selected style="color:#888;">SELECIONE...</option>
                    <option value="intoxicacao_alcoolica">Intoxicação alcoólica</option>
                    <option value="substancias_psicoativas">Uso de substâncias psicoativas</option>
                    <option value="traumatismo">Traumatismo (queda, briga, etc)</option>
                    <option value="ferimento_arma">Ferimento por arma</option>
                    <option value="dificuldade_respiratoria">Dificuldade respiratória</option>
                    <option value="mal_estar_desmaio">Mal-estar geral / desmaio</option>
                    <option value="Lesão cortante ">Lesão cortante </option>
                    <option value="pico hipertensivo">Pico hipertensivo</option>
                    <option value="Crise de ansiedade">Crise de ansiedade</option>
                    <option value="Síncope / Tontura">Síncope / Tontura</option>
                    <option value="Cefaléia">Cefaléia</option>
                    <option value="Dor no estômago">Dor no estômago</option>
                    <option value="Intoxicação alcoólica">Intoxicação alcoólica</option>
                    <option value="Náuseas / Vômitos">Náuseas / Vômitos</option>
                    <option value="Taquicardia">Taquicardia</option>
                    <option value="Mal estar">Mal estar</option>
                    <option value="Pico hipertensivo">Pico hipertensivo</option>
                    <option value="Hipotensão">Hipotensão</option>
                    <option value="Hiperglicemia">Hiperglicemia</option>
                    <option value="Hipoglicemia">Hipoglicemia</option>
                    <option value="Processo alérgico">Processo alérgico</option>
                    <option value="Taquicardia">Taquicardia</option>
                    <option value="Escorições">Escorições</option>
                    <option value="outra">Outra</option>
                </select>
            </div>
        </div>
        <div id="box_queixa_outra" class="hidden" style="margin-top:10px;">
            <label>Descrição da Queixa:</label><textarea name="queixa_descricao" id="input_queixa_outra" rows="2"></textarea>
        </div>
        <button type="submit" class="btn">REGISTRAR ENTRADA</button>
    </form>

    {{-- LISTAS --}}
    <div class="grid" style="grid-template-columns: 1fr 1fr;">
        <div class="card card-triagem">
            <h3>⏳ Aguardando Médico</h3>
            <table><tr><th>ID</th><th>Paciente</th><th>Ação</th></tr>
                @foreach($aguardando as $p)
                <tr>
                    <td><strong>{{ $p->id }}</strong></td>
                    <td>{{ $p->nome }}</td>
                    <td>
                        <a href="{{ route('pacientes.imprimir', $p->id) }}" target="_blank" class="btn-acao">🖨️</a> 
                        <a href="{{ route('pacientes.delete', $p->id) }}" onclick="return confirm('Apagar registro?')" class="btn-acao">🗑️</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="card card-observacao">
            <h3>⚠️ Em Observação (Já Medicado)</h3>
            <table><tr><th>ID</th><th>Paciente</th><th>Status</th></tr>
                @foreach($observacao as $p)
                <tr>
                    <td><strong>{{ $p->id }}</strong></td>
                    <td>{{ $p->nome }}</td>
                    <td><span class="badge badge-warning"><i class="bi bi-hourglass-split"></i> PENDENTE</span></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    {{-- 2. ATENDIMENTO --}}
    <form method="post" action="{{ route('pacientes.medico') }}" class="card">
        @csrf
        <h2>2. Atendimento Médico (Diagnóstico)</h2>
        
        <div class="grid" style="grid-template-columns: 0.5fr 3.5fr;">
            <div><label>ID Paciente*:</label><input type="number" name="id_paciente" required style="border: 2px solid #1351B4;"></div>
            <div><label>Diagnóstico Médico*:</label><textarea name="diagnostico" rows="1" required></textarea></div>
        </div>
        <div class="grid">
            <div><label>Alergias?</label><select name="alergia" id="ale">
                <option value="" style="color:#888;">SELECIONE...</option>
                <option value="NÃO">NÃO</option><option value="SIM">SIM</option>
            </select>
                <div id="b_ale" style="display:none; margin-top:5px;"><input name="alergia_descricao" placeholder="Quais?"></div>
            </div>
            <div><label>Notificação Compulsória?</label><select name="doenca_notificacao" id="not">
                <option value="" style="color:#888;">SELECIONE...</option>
                <option value="NÃO">NÃO</option><option value="SIM">SIM</option>
            </select>
                <div id="b_not" style="display:none; margin-top:5px;"><input name="doenca_descricao" placeholder="Qual doença?"></div>
            </div>
        </div>
        
        <div style="margin-top: 10px;">
             <label>Acidente Trabalho?</label><select name="acidente_trabalho">
                 <option value="" style="color:#888;">SELECIONE...</option>
                 <option value="NÃO">NÃO</option><option value="SIM">SIM</option>
             </select>
        </div>

        <div style="margin-top: 20px;">
            <label style="background: #e9ecef; padding: 8px; border-left: 4px solid #1351B4; display: block;">PLANO TERAPÊUTICO (Medicações e Conduta)</label>
            <textarea name="plano_terapeutico" rows="5" style="width:100%; margin-top:5px;"></textarea>
        </div>

        <button type="submit" class="btn">SALVAR E ENVIAR PARA OBSERVAÇÃO</button>
    </form>

    {{-- 3. FINALIZAÇÃO --}}
    <form method="post" action="{{ route('pacientes.final') }}" class="card">
        @csrf
        <h2><i class="bi bi-door-open-fill"></i> 3. Finalização de Atendimento (Desfecho)</h2>
        <p style="font-size:12px; color:#666; margin-bottom:10px;">* Só é possível finalizar se o paciente já tiver passado pelo passo 2 (Médico).</p>
        
        <div class="grid" style="grid-template-columns: 0.5fr 3.5fr;">
            <div><label>ID Paciente*:</label><input type="number" name="id_paciente_fim" required style="border: 2px solid #1351B4;"></div>
            <div>
                <label>Desfecho Final:</label>
                <select name="destino" id="dest" required>
                    <option value="" style="color:#888;">SELECIONE...</option>
                    <option value="ALTA">ALTA MÉDICA</option>
                    <option value="EVASÃO">EVASÃO</option>
                    <option value="TRANSFERIDO PARA">TRANSFERIDO PARA:</option>
                    <option value="OBITO">ÓBITO</option>
                    <option value="OUTROS">OUTROS</option>
                </select>
                <div id="b_dest" style="display:none; margin-top:5px;"><input name="destino_detalhe" placeholder="Local de destino"></div>
            </div>
        </div>
        
        <div style="margin-top:10px;">
            <label>Observação do Desfecho:</label>
            <input name="observacao_desfecho" placeholder="Ex: Receita entregue, orientações dadas.">
        </div>

        <button type="submit" class="btn">FINALIZAR (DAR ALTA)</button>
    </form>

    {{-- LISTA FINALIZADOS --}}
    <div class="card card-finalizados">
        <h3>✅ Últimos Finalizados</h3>
        <table><tr><th>ID</th><th>Paciente</th><th>Desfecho</th><th>Ação</th></tr>
            @foreach($finalizados as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->nome }}</td>
                <td><span class="badge badge-success"><i class="bi bi-check-circle"></i> CONCLUÍDO</span></td>
                <td>
                    <a href="{{ route('pacientes.imprimir', $p->id) }}" target="_blank" class="btn-acao">🖨️</a> 
                    <a href="{{ route('pacientes.delete', $p->id) }}" onclick="return confirm('Apagar registro?')" class="btn-acao">🗑️</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

</div>

<script>
    const setupToggle = (idS, idB, val) => {
        const s = document.getElementById(idS);
        if(s) s.addEventListener('change', function() {
            document.getElementById(idB).style.display = (this.value === val) ? 'block' : 'none';
        });
    }
    setupToggle('mun', 'b_mun', 'OUTRO');
    setupToggle('raca-select', 'box_raca_outros', 'outros');
    setupToggle('ale', 'b_ale', 'SIM');
    setupToggle('not', 'b_not', 'SIM');
    setupToggle('dest', 'b_dest', 'TRANSFERIDO PARA');
    document.getElementById('queixa-principal').addEventListener('change', function() {
        document.getElementById('box_queixa_outra').style.display = (this.value === 'outra') ? 'block' : 'none';
    });
    document.getElementById('fd').addEventListener('change', function() {
        const nasc = new Date(this.value); const hoje = new Date();
        let idade = hoje.getFullYear() - nasc.getFullYear();
        if (hoje.getMonth() < nasc.getMonth() || (hoje.getMonth() == nasc.getMonth() && hoje.getDate() < nasc.getDate())) idade--;
        document.getElementById('fi').value = idade >= 0 ? idade : 0;
    });
    document.getElementById('ni').onchange = function() {
        ['fn', 'fs', 'fm', 'fc', 'fd', 'fi'].forEach(id => { 
            const element = document.getElementById(id);
            if(element) {
                element.disabled = this.checked; 
                if(this.checked) element.value = ''; 
            }
        });
    };
</script>
</body>
</html>