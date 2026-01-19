<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Atendimento #{{ $p->id }}</title>
    <style>
        @page { size: A4; margin: 10mm; } 
        body { font-family: Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #000; }
        .topo { text-align: center; border-bottom: 2px solid #1351B4; margin-bottom: 10px; padding-bottom: 2px; }
        .titulo { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #1351B4; }
        .secao { background: #f0f4f8; padding: 3px 10px; font-weight: bold; border-left: 5px solid #1351B4; margin-top: 8px; text-transform: uppercase; border-bottom: 1px solid #ccc; font-size: 11px; }
        .grid { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 3px; }
        .campo { flex: 1; min-width: 150px; border-bottom: 1px solid #eee; padding: 2px 0; }
        .label { font-weight: bold; font-size: 9px; color: #555; text-transform: uppercase; display: block; }
        .valor { font-size: 12px; }
        .caixa { border: 1px solid #000; padding: 5px; margin-top: 3px; min-height: 30px; }
        footer { position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; }
        footer img { width: 100%; max-height: 60px; object-fit: contain; }
        @media print { 
            .no-print { display: none; } 
            footer { position: fixed; bottom: 0; } 
            .caixa, .grid { page-break-inside: avoid; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="topo">
        <div class="titulo">Prefeitura de São Luís - MA</div>
        <div>Secretaria Municipal de Saúde (SEMUS)</div>
        <div style="margin-top:2px; font-weight:bold;">FICHA DE ATENDIMENTO Nº {{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="secao">1. IDENTIFICAÇÃO E TRIAGEM</div>
    <div class="grid">
        <div class="campo" style="flex: 1.5;"><span class="label">Nome Civil:</span> <span class="valor">{{ $p->nome }}</span></div>
        <div class="campo" style="flex: 1.5;"><span class="label">Nome Social:</span> <span class="valor">{{ $p->nome_social ?: '---' }}</span></div>
        <div class="campo"><span class="label">CPF:</span> <span class="valor">{{ $p->cpf }}</span></div>
    </div>
    <div class="grid">
        <div class="campo"><span class="label">Data Nasc:</span> <span class="valor">{{ $p->data_nascimento }}</span></div>
        <div class="campo"><span class="label">Idade:</span> <span class="valor">{{ $p->idade }} Anos</span></div>
        <div class="campo"><span class="label">Sexo:</span> <span class="valor">{{ $p->sexo }}</span></div>
    </div>
    <div class="grid">
        <div class="campo"><span class="label">Nome da Mãe:</span> <span class="valor">{{ $p->mae }}</span></div>
        <div class="campo"><span class="label">Raça/Cor:</span> <span class="valor">{{ str_replace('Outros: ', '', $p->raca) }}</span></div>
        <div class="campo"><span class="label">Escolaridade:</span> <span class="valor">{{ $p->escolaridade }}</span></div>
    </div>
    <div class="grid">
        <div class="campo" style="flex: 2;"><span class="label">Endereço:</span> <span class="valor">{{ $p->endereco }}</span></div>
        <div class="campo"><span class="label">Município:</span> <span class="valor">{{ $p->municipio }}</span></div>
        <div class="campo"><span class="label">Ocupação:</span> <span class="valor">{{ $p->ocupacao ?: '---' }}</span></div>
    </div>
    <div class="grid">
        <div class="campo"><span class="label">UF:</span> <span class="valor">{{ $p->uf ?: 'MA' }}</span></div>
        <div class="campo"><span class="label">CEP:</span> <span class="valor">{{ $p->cep ?: '---' }}</span></div>
        <div class="campo"><span class="label">Telefone:</span> <span class="valor">{{ $p->telefone ?: '---' }}</span></div>
        <div class="campo"><span class="label">Hora de Entrada:</span> <span class="valor">{{ $p->horario_entrada }}</span></div>
    </div>

    <div class="secao">AFERIÇÃO MÉDICA (SINAIS VITAIS E OBSERVAÇÕES)</div>
    <div class="caixa" style="min-height: 25px; background-color: #fcfcfc;">
        {!! nl2br(e($p->afericao ?? '---')) !!}
    </div>

    <div class="secao">2. Queixa Principal</div>
    <div class="caixa">{!! nl2br(e($p->queixa)) !!}</div>

    @if(!empty($p->diagnostico))
        <div class="secao">3. ATENDIMENTO MÉDICO</div>
        <div class="caixa"><b>DIAGNÓSTICO:</b><br>{!! nl2br(e($p->diagnostico)) !!}</div>
        
        <div class="grid">
            <div class="campo"><span class="label">Alergias:</span> <span class="valor">{{ ($p->alergia === 'SIM') ? 'SIM - ' . $p->alergia_descricao : $p->alergia }}</span></div>
            <div class="campo"><span class="label">Notificação Compulsória:</span> <span class="valor">{{ ($p->doenca_notificacao === 'SIM') ? 'SIM - ' . $p->doenca_descricao : $p->doenca_notificacao }}</span></div>
        </div>
        <div class="campo"><span class="label">Acidente Trabalho:</span> <span class="valor">{{ $p->acidente_trabalho }}</span></div>

        <div class="secao">Plano Terapêutico Registrado</div>
        <div class="caixa" style="min-height: 60px;">
            {!! nl2br(e($p->plano_terapeutico)) !!}
        </div>

        @if($p->status === 'Finalizado')
        <div class="secao">4. DESFECHO / ALTA</div>
        <div class="grid">
             <div class="campo"><span class="label">DESFECHO:</span> <span class="valor">{{ $p->destino }} {{ $p->destino_detalhe }}</span></div>
             <div class="campo" style="flex:2;"><span class="label">OBSERVAÇÃO FINAL:</span> <span class="valor">{{ $p->observacao_desfecho }}</span></div>
        </div>
        @endif

    @endif

    <div style="margin-top: 50px; display: flex; justify-content: space-around; text-align: center; page-break-inside: avoid;">
        <div style="border-top: 1px solid #000; width: 200px; padding-top: 5px;">Responsável Triagem</div>
        <div style="border-top: 1px solid #000; width: 200px; padding-top: 5px;">Carimbo/Assinatura Médico</div>
    </div>
    
    <footer><img src="{{ asset('rodape.png.png') }}"></footer>
</body>
</html>