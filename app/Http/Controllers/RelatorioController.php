<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;

class RelatorioController extends Controller
{
    public function index(Request $request)
    {
        $query = Paciente::where('status', 'Finalizado');

        if ($request->filled('data_inicio')) $query->whereDate('data_registro', '>=', $request->data_inicio);
        if ($request->filled('sexo')) $query->where('sexo', $request->sexo);
        // ... outros filtros

        $pacientes = $query->orderBy('data_registro', 'desc')->get();

        return view('relatorios.index', compact('pacientes'));
    }

    public function gerarPdf(Request $request)
    {
        // Mesma lógica de filtro do index
        $query = Paciente::where('status', 'Finalizado');
        if ($request->filled('data_inicio')) $query->whereDate('data_registro', '>=', $request->data_inicio);
        
        $pacientes = $query->get();

        // Retorna aquela view de PDF que você já tinha, mas renderizada
        return view('relatorios.pdf', compact('pacientes'));
    }
}