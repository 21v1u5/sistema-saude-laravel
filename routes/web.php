<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RelatorioController;

// Tela Principal (Sistema)
Route::get('/', [PacienteController::class, 'index'])->name('sistema.index');

// Ações do Paciente (CRUD)
Route::post('/triagem', [PacienteController::class, 'store'])->name('pacientes.store');
Route::post('/atendimento', [PacienteController::class, 'updateMedico'])->name('pacientes.medico');
Route::post('/finalizacao', [PacienteController::class, 'updateFinal'])->name('pacientes.final');
Route::get('/excluir/{id}', [PacienteController::class, 'destroy'])->name('pacientes.delete'); // Mudado para GET para simplificar links, mas ideal é DELETE
Route::get('/imprimir/{id}', [PacienteController::class, 'imprimir'])->name('pacientes.imprimir');

// Dashboards e Relatórios
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
Route::get('/relatorios/pdf', [RelatorioController::class, 'gerarPdf'])->name('relatorios.pdf');