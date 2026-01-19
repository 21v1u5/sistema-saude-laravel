<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatórios Gerenciais - SEMUS</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #E5E9F2; margin: 0; padding: 20px; color: #444; }
        .card { background: #fff; padding: 30px; border-radius: 10px; max-width: 800px; margin: 0 auto; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 5px solid #1351B4; }
        header { text-align: center; margin-bottom: 30px; }
        header img { height: 50px; margin-bottom: 10px; }
        h2 { color: #1351B4; text-transform: uppercase; margin-top: 0; text-align: center; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 12px; text-transform: uppercase; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn { display: block; width: 100%; padding: 15px; border: none; border-radius: 5px; color: white; font-weight: bold; cursor: pointer; margin-top: 10px; text-align: center; text-decoration: none; }
        .btn-pdf { background: #1351B4; } .btn-pdf:hover { background: #0e3c85; }
        .btn-voltar { background: #6c757d; width: auto; display: inline-block; margin-top: 20px; padding: 10px 20px; }
    </style>
</head>
<body>
    <div class="card">
        <header>
            <img src="{{ asset('logo_sao_luis.png') }}" alt="Logo">
            <h2>Relatórios Gerenciais</h2>
        </header>

        {{-- O action aponta para a rota que gera o PDF --}}
        <form action="{{ route('relatorios.pdf') }}" method="GET" target="_blank">
            <div class="grid">
                <div>
                    <label>Data Inicial</label>
                    <input type="date" name="data_inicio">
                </div>
                <div>
                    <label>Data Final</label>
                    <input type="date" name="data_fim">
                </div>
            </div>
            
            <button type="submit" class="btn btn-pdf">📄 GERAR RELATÓRIO PDF (A4)</button>
        </form>

        <div style="text-align: center;">
            <a href="{{ route('sistema.index') }}" class="btn btn-voltar">⬅ Voltar ao Sistema</a>
        </div>
    </div>
</body>
</html>