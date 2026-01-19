# 🏥 Sistema de Gestão de Atendimento (SGA) - Versão Laravel

![Laravel](https://img.shields.io/badge/Laravel-10%2B-red) ![PHP](https://img.shields.io/badge/PHP-8.2-blue) ![SQLite](https://img.shields.io/badge/Database-SQLite-lightgrey)

Sistema web para triagem, atendimento médico e monitoramento de pacientes em tempo real. Desenvolvido para cenários de alta demanda, focado em agilidade e **zero dependência de infraestrutura complexa** (roda localmente com SQLite).

---

## 🚀 Funcionalidades
1.  **Triagem:** Cadastro rápido, modo "Não Identificado" e registro de sinais vitais.
2.  **Consultório:** Prontuário simplificado, alertas de alergia/notificação e conduta.
3.  **Gestão (BI):** Dashboard com gráficos e relatórios gerenciais (PDF/Excel).

---

## 📦 Instalação e Configuração (Passo a Passo)

Siga estes passos no terminal para rodar o projeto do zero:

### 1. Instalar Dependências
```bash
git clone [https://github.com/SEU-USUARIO/NOME-DO-REPO.git](https://github.com/SEU-USUARIO/NOME-DO-REPO.git)
cd NOME-DO-REPO
composer install
```
2. Configurar Ambiente (.env)
```bash

copy .env.example .env
php artisan key:generate
```
3. Configurar Banco de Dados (SQLite)
No arquivo .env, altere para: DB_CONNECTION=sqlite (apague as linhas DB_HOST, DB_PORT, etc).

Crie um arquivo vazio: database/database.sqlite.

4. Finalizar Instalação
```bash

php artisan migrate
```
(Coloque as imagens logo_sao_luis.png e rodape.png.png na pasta public/).

▶️ Como Rodar
```bash

php artisan serve
```

Acesse: http://127.0.0.1:8000