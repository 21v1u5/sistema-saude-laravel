<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel de Monitoramento - SEMUS</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script> 
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #E5E9F2; margin: 0; padding: 0; color: #333; }
        header { background: #1351B4; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 25px; }
        header img { height: 55px; background: white; padding: 5px; border-radius: 8px; margin-bottom: 10px; }
        header h1 { margin: 0; font-size: 24px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        header .subtitulo { font-size: 14px; opacity: 0.9; margin-top: 5px; }
        .intro-text { background-color: #fff; border-left: 5px solid #1351B4; border-radius: 5px; padding: 20px; margin: 0 auto 30px; max-width: 1300px; color: #555; text-align: justify; font-size: 14px; line-height: 1.6; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .filter-bar { background: #fff; padding: 20px; border-radius: 8px; margin: 0 auto 30px; max-width: 1300px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-top: 3px solid #1351B4; display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end; }
        .f-group { flex: 1 1 150px; }
        .f-group label { display: block; font-size: 12px; font-weight: bold; margin-bottom: 8px; color: #1351B4; text-transform: uppercase; }
        .f-group select, .f-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; background: #f9f9f9; box-sizing: border-box; }
        .btn-filter { background: #1351B4; color: white; border: none; padding: 12px 25px; border-radius: 4px; font-weight: bold; cursor: pointer; transition: 0.2s; white-space: nowrap; flex: 1 1 100px; }
        .btn-clear { background: #eee; padding: 12px 20px; border-radius: 4px; text-decoration: none; color: #555; font-size: 13px; border: 1px solid #ccc; font-weight: bold; white-space: nowrap; text-align: center; flex: 0 1 auto; }
        .container { max-width: 1350px; margin: 0 auto; padding: 0 20px 40px; }
        .grid-charts { display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 3px 10px rgba(0,0,0,0.08); padding: 20px; border: 1px solid #eee; }
        .card-header { background-color: #e3f2fd; color: #1351B4; border-bottom: 2px solid #1351B4; font-weight: 700; padding: 10px 15px; border-radius: 5px 5px 0 0; margin: -20px -20px 15px -20px; font-size: 14px; text-transform: uppercase; display: flex; justify-content: space-between; align-items: center; }
        .total-badge { background-color: #1351B4; padding: 4px 12px; border-radius: 15px; font-size: 11px; color: #fff; font-weight: 600; }
        .chart-box { position: relative; height: 300px; width: 100%; }
        .float-back { position: fixed; bottom: 20px; right: 20px; background: #6c757d; color: white; padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 15px rgba(0,0,0,0.3); z-index: 999; transition: 0.3s; }
        @media (max-width: 768px) { .grid-charts { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<header>
    <img src="{{ asset('logo_sao_luis.png') }}" alt="Logo SEMUS">
    <h1>Painel de Monitoramento</h1>
    <div class="subtitulo">SEMUS - Secretaria Municipal de Saúde</div>
</header>

<div class="intro-text">
    <strong>Resumo Geral:</strong> Dados consolidados do sistema. "NÃO IDENTIFICADO" indica campos não preenchidos.
</div>

<form method="get" class="filter-bar">
    <div class="f-group"><label>De</label><input type="date" name="data_inicio" value="{{ request('data_inicio') }}"></div>
    <div class="f-group"><label>Até</label><input type="date" name="data_fim" value="{{ request('data_fim') }}"></div>
    <div class="f-group"><label>Sexo</label>
        <select name="sexo">
            <option value="">Todos</option>
            <option value="MASCULINO" {{ request('sexo')=='MASCULINO'?'selected':'' }}>Masculino</option>
            <option value="FEMININO" {{ request('sexo')=='FEMININO'?'selected':'' }}>Feminino</option>
        </select>
    </div>
    <div class="f-group" style="flex: 0 0 auto; display: flex; gap: 10px; padding-bottom: 2px;">
        <button type="submit" class="btn-filter">FILTRAR</button>
        <a href="{{ route('dashboard') }}" class="btn-clear">Limpar</a>
    </div>
</form>

<div class="container">
    <div class="grid-charts">
        <div class="card">
            <div class="card-header"><span>Atendimentos por Dia</span><span class="total-badge">Total: {{ $totalGeral }}</span></div>
            <div class="chart-box"><canvas id="cDia"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header"><span>Atendimentos por Sexo</span><span class="total-badge">Total: {{ $totalGeral }}</span></div>
            <div class="chart-box"><canvas id="cSexo"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header"><span>Raça/Cor</span><span class="total-badge">Total: {{ $totalGeral }}</span></div>
            <div class="chart-box"><canvas id="cRaca"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header"><span>Faixa Etária</span><span class="total-badge">Total: {{ $totalGeral }}</span></div>
            <div class="chart-box"><canvas id="cIdade"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header"><span>Acidente de Trabalho</span><span class="total-badge">Confirmados: {{ $totTrab }}</span></div>
            <div class="chart-box"><canvas id="cTrab"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header"><span>Top Ocupações</span><span class="total-badge">Top 10</span></div>
            <div class="chart-box"><canvas id="cOcup"></canvas></div>
        </div>
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card-header"><span>Desfecho do Atendimento</span><span class="total-badge">Finalizados: {{ $totDesf }}</span></div>
            <div class="chart-box"><canvas id="cDesf"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header"><span>Município de Residência</span><span class="total-badge">Top 10</span></div>
            <div class="chart-box"><canvas id="cMun"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header"><span>Notificação Compulsória</span><span class="total-badge">Confirmados: {{ $totNotif }}</span></div>
            <div class="chart-box"><canvas id="cNotif"></canvas></div>
        </div>
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card-header" style="background-color: #F4D03F; color: #000;"><span>Queixa Principal (Top 10)</span></div>
            <div class="chart-box"><canvas id="cQueixa"></canvas></div>
        </div>
    </div>
</div>

<a href="{{ route('sistema.index') }}" class="float-back">⬅ Voltar</a>

<script>
    Chart.defaults.font.family = "'Segoe UI', sans-serif";
    Chart.defaults.font.size = 11;
    Chart.register(ChartDataLabels);

    const corAzul = '#1351B4'; const corVerde = '#28a745'; const corLaranja = '#e67e22'; const corCinza = '#95a5a6';
    const optsHoriz = { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, datalabels: { color: 'black', anchor: 'end', align: 'end', formatter: v=>v>0?v:'' } } };
    const optsVert = { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, datalabels: { color: 'black', anchor: 'end', align: 'top', formatter: v=>v>0?v:'' } } };

    // Gráfico de Dia
    new Chart(document.getElementById('cDia'), { 
        type: 'bar', 
        data: { labels: @json($porDia->keys()), datasets: [{ data: @json($porDia->values()), backgroundColor: corAzul }] }, 
        options: optsHoriz 
    });

    // Gráfico de Sexo
    new Chart(document.getElementById('cSexo'), { 
        type: 'bar', 
        data: { 
            labels: ['Sexo'], 
            datasets: [
                { label: 'Masculino', data: [{{ $porSexo['MASCULINO'] }}], backgroundColor: corAzul },
                { label: 'Feminino', data: [{{ $porSexo['FEMININO'] }}], backgroundColor: corVerde }
            ]
        }, 
        options: { ...optsHoriz, scales: { x: { stacked: true }, y: { stacked: true } }, plugins: { legend: { display: true } } } 
    });

    // Raça
    new Chart(document.getElementById('cRaca'), { type: 'bar', data: { labels: @json($porRaca->keys()), datasets: [{ data: @json($porRaca->values()), backgroundColor: [corAzul, corVerde, corLaranja, corCinza] }] }, options: optsHoriz });

    // Idade
    new Chart(document.getElementById('cIdade'), { type: 'bar', data: { labels: @json(array_keys($faixaEtaria)), datasets: [{ data: @json(array_values($faixaEtaria)), backgroundColor: corAzul }] }, options: optsHoriz });

    // Função de Cores
    function getColorArray(labels, targetYes) {
        return labels.map(l => l.includes(targetYes) ? '#d9534f' : '#28a745');
    }

    // Trabalho
    const lTrab = @json($porTrab->keys());
    new Chart(document.getElementById('cTrab'), { type: 'bar', data: { labels: lTrab, datasets: [{ data: @json($porTrab->values()), backgroundColor: getColorArray(lTrab, 'SIM') }] }, options: optsVert });

    // Ocupação
    new Chart(document.getElementById('cOcup'), { type: 'bar', data: { labels: @json($porOcupacao->keys()), datasets: [{ data: @json($porOcupacao->values()), backgroundColor: corAzul }] }, options: optsHoriz });

    // Desfecho
    new Chart(document.getElementById('cDesf'), { type: 'bar', data: { labels: @json($porDesfecho->keys()), datasets: [{ data: @json($porDesfecho->values()), backgroundColor: corVerde }] }, options: optsHoriz });

    // Município
    new Chart(document.getElementById('cMun'), { type: 'bar', data: { labels: @json($porMun->keys()), datasets: [{ data: @json($porMun->values()), backgroundColor: corAzul }] }, options: optsHoriz });

    // Notificação
    const lNotif = @json($porNotif->keys());
    new Chart(document.getElementById('cNotif'), { type: 'bar', data: { labels: lNotif, datasets: [{ data: @json($porNotif->values()), backgroundColor: getColorArray(lNotif, 'SIM') }] }, options: optsVert });

    // Queixa
    new Chart(document.getElementById('cQueixa'), { 
        type: 'bar', 
        data: { labels: @json($porQueixa->keys()), datasets: [{ label: 'Ocorrências', data: @json($porQueixa->values()), backgroundColor: '#F1C40F' }] }, 
        options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } } 
    });
</script>
</body>
</html>