# 🏥 Sistema de Gestão de Atendimento (SGA) - Versão Laravel

![Laravel](https://img.shields.io/badge/Laravel-10%2B-red) ![PHP](https://img.shields.io/badge/PHP-8.2-blue) ![SQLite](https://img.shields.io/badge/Database-SQLite-lightgrey)

Sistema web para triagem, atendimento médico e monitoramento de pacientes em tempo real. Desenvolvido para cenários de alta demanda (Hospitais de Campanha e Eventos), focado em agilidade e **zero dependência de infraestrutura complexa** (roda localmente com SQLite).

---

## 🚀 Funcionalidades

### 1. Triagem e Recepção
* **Cadastro Ágil:** Registro rápido de pacientes com padronização automática de dados.
* **Modo "Não Identificado":** Fluxo específico para pacientes sem documentos (gera ID anônimo).
* **Sinais Vitais:** Campo para registro de PA, Temperatura, Saturação, etc.

### 2. Atendimento Médico (Consultório)
* **Prontuário Simplificado:** Diagnóstico e Conduta Terapêutica.
* **Alertas de Segurança:** Tags visuais para **Alergias** e **Notificação Compulsória**.
* **Vigilância:** Registro de Acidentes de Trabalho.

### 3. Gestão e Monitoramento (BI)
* **Dashboard:** Gráficos em tempo real (Fluxo por dia, Sexo, Faixa Etária, Queixas Principais).
* **Relatórios:** Geração de PDF (Ficha de Atendimento) e listagens auditáveis.

---

## 📦 Como Instalar e Rodar (Passo a Passo)

Siga estes passos no seu terminal (PowerShell, CMD ou Git Bash):

### 1. Baixar e Instalar Dependências
```bash
# 1. Clone este repositório
git clone [https://github.com/SEU-USUARIO/NOME-DO-REPO.git](https://github.com/SEU-USUARIO/NOME-DO-REPO.git)

# 2. Entre na pasta
cd NOME-DO-REPO

# 3. Instale as bibliotecas do Laravel
composer install

```
### 2. Configurar o Ambiente
O Laravel precisa de um arquivo .env com as configurações locais.

```bash

# 1. Crie uma cópia do exemplo
copy .env.example .env

# 2. Gere a chave de segurança
php artisan key:generate
```


### 3. Configurar o Banco de Dados (SQLite)
Este projeto usa SQLite para facilitar a portabilidade (um arquivo único ao invés de um servidor MySQL).

a. Abra o arquivo .env e configure a conexão assim (apague as linhas DB_HOST, DB_PORT, etc.):

```bash

DB_CONNECTION=sqlite
```
b. Crie o arquivo do banco:

Vá na pasta database do projeto.

Crie um arquivo vazio chamado database.sqlite.

(Dica no Windows: Botão direito > Novo Documento de Texto > Renomeie para database.sqlite e apague o .txt do final).

### 4. Criar as Tabelas e Imagens
Rode a migração para criar a estrutura do banco:

```bash

php artisan migrate

```

(Se perguntar "Would you like to create it?", digite yes).

Imagens: Certifique-se de que os arquivos logo_sao_luis.png e rodape.png.png estejam dentro da pasta public/.

### ▶️ Como Usar
Com tudo configurado, inicie o servidor local:

```bash

php artisan serve
```
Agora acesse no seu navegador: 👉 https://www.google.com/search?q=http://127.0.0.1:8000

### 📂 Estrutura de Pastas Importantes
app/Http/Controllers/: Onde fica a lógica (Paciente, Dashboard, Relatórios).

app/Models/: Modelos de dados (Paciente.php).

resources/views/: Onde ficam as telas (HTML/Blade).

database/migrations/: Definição da estrutura do banco de dados.

public/: Onde ficam as imagens e arquivos estáticos acessíveis.

### 🧪 Rodando Testes
Para garantir que o fluxo de triagem não quebre com alterações:

```bash

php artisan test
```