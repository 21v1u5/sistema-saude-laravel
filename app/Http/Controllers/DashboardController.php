<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Paciente::query();

        // Filtros
        if ($request->filled('data_inicio')) $query->whereDate('data_registro', '>=', $request->data_inicio);
        if ($request->filled('data_fim')) $query->whereDate('data_registro', '<=', $request->data_fim);
        if ($request->filled('sexo')) $query->where('sexo', $request->sexo);
        if ($request->filled('faixa_etaria')) {
            $fe = $request->faixa_etaria;
            if($fe == '0-11') $query->where('idade', '<=', 11);
            elseif($fe == '12-17') $query->whereBetween('idade', [12, 17]);
            elseif($fe == '18-25') $query->whereBetween('idade', [18, 25]);
            elseif($fe == '26-30') $query->whereBetween('idade', [26, 30]);
            elseif($fe == '31-40') $query->whereBetween('idade', [31, 40]);
            elseif($fe == '41-60') $query->whereBetween('idade', [41, 60]);
            elseif($fe == '60+')  $query->where('idade', '>', 60);
        }

        $dados = $query->get();
        $totalGeral = $dados->count();

        // 1. Por Dia
        $porDia = $dados->groupBy(fn($d) => $d->data_registro ? date('d/m', strtotime($d->data_registro)) : 'N/I')->map->count();

        // 2. Sexo (Separado por dia para o gráfico empilhado ou simples)
        // Simplifiquei para total por sexo para facilitar, mas mantive a estrutura se quiser evoluir
        $porSexo = [
            'MASCULINO' => $dados->where('sexo', 'MASCULINO')->count(),
            'FEMININO' => $dados->where('sexo', 'FEMININO')->count()
        ];

        // 3. Raça
        $porRaca = $dados->groupBy('raca')->map->count()->sortDesc();

        // 4. Faixa Etária (Calculada no PHP)
        $faixaEtaria = [
            '0-11' => $dados->where('idade', '<=', 11)->count(),
            '12-17' => $dados->whereBetween('idade', [12, 17])->count(),
            '18-25' => $dados->whereBetween('idade', [18, 25])->count(),
            '26-30' => $dados->whereBetween('idade', [26, 30])->count(),
            '31-40' => $dados->whereBetween('idade', [31, 40])->count(),
            '41-60' => $dados->whereBetween('idade', [41, 60])->count(),
            '>60'   => $dados->where('idade', '>', 60)->count(),
        ];

        // 5. Acidente de Trabalho
        $porTrab = $dados->groupBy('acidente_trabalho')->map->count()->sortDesc();

        // 6. Ocupação (Top 10)
        $porOcupacao = $dados->whereNotIn('ocupacao', ['NÃO IDENTIFICADO', ''])->groupBy('ocupacao')->map->count()->sortDesc()->take(10);

        // 7. Desfecho
        $porDesfecho = $dados->where('status', 'Finalizado')->groupBy('destino')->map->count()->sortDesc();

        // 8. Município (Top 10)
        $porMun = $dados->whereNotIn('municipio', ['NÃO IDENTIFICADO', ''])->groupBy('municipio')->map->count()->sortDesc()->take(10);

        // 9. Notificação Compulsória
        $porNotif = $dados->groupBy('doenca_notificacao')->map->count()->sortDesc();

        // 10. Queixa (Top 10)
        $porQueixa = $dados->whereNotIn('queixa', ['NÃO IDENTIFICADO', ''])->groupBy('queixa')->map->count()->sortDesc()->take(10);

        // Totais específicos
        $totTrab = $dados->where('acidente_trabalho', 'SIM')->count();
        $totNotif = $dados->where('doenca_notificacao', 'SIM')->count();
        $totDesf = $dados->where('status', 'Finalizado')->count();

        return view('dashboard', compact(
            'totalGeral', 'totTrab', 'totNotif', 'totDesf',
            'porDia', 'porSexo', 'porRaca', 'faixaEtaria', 
            'porTrab', 'porOcupacao', 'porDesfecho', 'porMun', 'porNotif', 'porQueixa'
        ));
    }
}