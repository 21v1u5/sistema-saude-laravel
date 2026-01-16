<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Paciente extends Model
{
    use HasFactory;

    // Nome da tabela no banco (opcional, o Laravel assume 'pacientes', mas é bom garantir)
    protected $table = 'pacientes';

    // Lista de campos que podem ser salvos em massa (Segurança)
    // Baseado na lista $colunas do seu sistema-saude.php
    protected $fillable = [
        // Identificação
        'nome', 
        'nome_social', 
        'cpf', 
        'data_nascimento', 
        'idade', 
        'sexo',
        'mae', 
        'telefone', 
        'endereco', 
        'cep', 
        'uf', 
        'municipio', 
        'nao_identificado',
        'horario_entrada', 
        
        // Triagem e Dados Clínicos
        'queixa', 
        'afericao', // Sinais vitais
        'escolaridade', 
        'raca', 
        'ocupacao',

        // Atendimento Médico
        'diagnostico', 
        'plano_terapeutico', 
        'alergia', 
        'alergia_descricao',
        'doenca_notificacao', 
        'doenca_descricao', 
        'acidente_trabalho',

        // Desfecho e Controle
        'destino', 
        'destino_detalhe', 
        'observacao_desfecho', 
        'carimbo_medico',
        'status', 
        'data_registro'
    ];

    // Configuração de datas (para o Laravel gerenciar created_at/updated_at corretamente)
    public $timestamps = true;

    // Mutators: Funções para garantir que os dados sejam salvos em MAIÚSCULO automaticamente
    // Isso substitui parte da sua função 'tratarCampo' do sistema antigo.

    protected function nome(): Attribute { return $this->upperCaseTrait(); }
    protected function nomeSocial(): Attribute { return $this->upperCaseTrait(); }
    protected function mae(): Attribute { return $this->upperCaseTrait(); }
    protected function endereco(): Attribute { return $this->upperCaseTrait(); }
    protected function ocupacao(): Attribute { return $this->upperCaseTrait(); }
    protected function diagnostico(): Attribute { return $this->upperCaseTrait(); }
    protected function queixa(): Attribute { return $this->upperCaseTrait(); }
    protected function planoTerapeutico(): Attribute { return $this->upperCaseTrait(); }
    protected function alergiaDescricao(): Attribute { return $this->upperCaseTrait(); }
    protected function doencaDescricao(): Attribute { return $this->upperCaseTrait(); }
    protected function destinoDetalhe(): Attribute { return $this->upperCaseTrait(); }
    protected function observacaoDesfecho(): Attribute { return $this->upperCaseTrait(); }

    // Helper para não repetir código
    private function upperCaseTrait()
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value ?? ''),
        );
    }
}