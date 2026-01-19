<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Geral</title>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 8pt; color: #000; }
        header { background-color: #1351B4; color: white; padding: 15px; text-align: center; margin-bottom: 20px; }
        header h1 { margin: 0; font-size: 14pt; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th { background: #eee; border: 1px solid #000; padding: 5px; text-align: left; }
        td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        .tag-alerta { color: #d9534f; font-weight: bold; }
        tr { page-break-inside: avoid; }
    </style>
</head>
<body onload="window.print()">
    <header>
        <h1>RELATÓRIO GERAL DE ATENDIMENTOS</h1>
        <small>Gerado em: {{ date('d/m/Y H:i') }}</small>
    </header>

    <table>
        <thead>
            <tr>
                <th width="10%">DATA</th>
                <th width="25%">PACIENTE</th>
                <th width="25%">TRIAGEM</th>
                <th width="25%">MÉDICO</th>
                <th width="15%">DESFECHO</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pacientes as $p)
            <tr>
                <td>{{ date('d/m/y H:i', strtotime($p->data_registro)) }}</td>
                <td>
                    <b>{{ $p->nome }}</b><br>
                    {{ $p->idade }} Anos | {{ $p->sexo }}
                </td>
                <td>
                    <b>QP:</b> {{ $p->queixa }}<br>
                    <i>{{ $p->afericao }}</i>
                </td>
                <td>
                    {{ $p->diagnostico }}<br>
                    @if($p->alergia == 'SIM') <span class="tag-alerta">⚠ Alergia</span> @endif
                    @if($p->doenca_notificacao == 'SIM') <span class="tag-alerta">⚠ Notificação</span> @endif
                </td>
                <td>{{ $p->destino }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center">Nenhum registro encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>