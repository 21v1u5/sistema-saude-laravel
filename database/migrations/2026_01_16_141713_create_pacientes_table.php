<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); 
            $table->string('nome_social')->nullable();
            $table->string('cpf')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->integer('idade')->nullable();
            $table->string('sexo')->nullable();
            $table->string('mae')->nullable();
            $table->string('telefone')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cep')->nullable();
            $table->string('uf')->nullable();
            $table->string('municipio')->nullable();
            $table->boolean('nao_identificado')->default(false);
            $table->time('horario_entrada')->nullable();
            $table->string('queixa')->nullable();
            $table->text('queixa_descricao')->nullable();
            $table->text('afericao')->nullable();
            $table->string('escolaridade')->nullable();
            $table->string('raca')->nullable();
            $table->string('ocupacao')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('plano_terapeutico')->nullable();
            $table->string('alergia')->default('NÃO');
            $table->string('alergia_descricao')->nullable();
            $table->string('doenca_notificacao')->default('NÃO');
            $table->string('doenca_descricao')->nullable();
            $table->string('acidente_trabalho')->default('NÃO');
            $table->string('destino')->nullable();
            $table->string('destino_detalhe')->nullable();
            $table->text('observacao_desfecho')->nullable();
            $table->string('carimbo_medico')->nullable();
            $table->string('status')->default('Aguardando');
            $table->dateTime('data_registro')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
