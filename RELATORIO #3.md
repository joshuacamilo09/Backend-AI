# Relatório Individual — Projeto BackendAI

## 👥 Elementos do Grupo
- Joshua
- Filomeno
- Alex

---

# 📌 Divisão de Tarefas

## 🧠 Joshua — Backend e Arquitetura do Sistema

### Responsabilidades:
- Implementação do backend em Laravel
- Criação das rotas API e lógica de negócio
- Integração com IA (Gemini) para geração de specifications
- Geração automática de projetos Laravel
- Criação do sistema de exportação em ZIP
- Gestão da base de dados (Projects, Generations, Specifications)

### Contribuições para o relatório:

#### ✔ Validação da Base de Dados
- Definição das entidades:
  - Project
  - Generation
  - Specification
- Relações entre tabelas:
  - 1 Project → 1 Specification
  - 1 Project → N Generations
- Validação através da estrutura gerada pela IA e armazenamento correto dos dados

#### ✔ Servidor (Backend)
- Recebe descrição do utilizador
- Processa com IA
- Guarda na base de dados
- Gera backend automaticamente
- Devolve resposta JSON ao frontend

---

## 🎨 Filomeno — Frontend e Interface do Utilizador

### Responsabilidades:
- Implementação das páginas HTML
- Design e estrutura do dashboard
- Criação das telas:
  - Dashboard
  - Generate Backend
  - Projects
  - Templates
  - Documentation
  - Settings
- Integração visual com dados do backend
- Melhorias de UX/UI

### Contribuições para o relatório:

#### ✔ Página HTML (Frontend)
- Consome dados via fetch (AJAX)
- Mostra:
  - Lista de projetos
  - Estado das gerações
  - Botão de download do ZIP
- Atualização dinâmica da interface

#### ✔ Validação do Sistema (Frontend)
- Demonstra que os dados chegam corretamente ao browser
- Exibição em tempo real no dashboard

---

## 🔗 Alex — Integração, Testes e Gestão do Projeto

### Responsabilidades:
- Ligação entre frontend e backend
- Testes das rotas e endpoints
- Correção de erros (CORS, autenticação, etc.)
- Validação do fluxo completo
- Organização do trabalho em equipa

### Contribuições para o relatório:

#### ✔ Validação do Sistema Completo
- Testes com:
  - Browser
  - Postman
- Verificação do fluxo:
  1. Input do utilizador
  2. Backend processa
  3. Dados guardados
  4. Dados exibidos no frontend

#### ✔ Origem dos Dados
- Dados gerados dinamicamente por IA (Gemini)
- Estrutura baseada em descrição do utilizador
- Dados armazenados na base de dados Laravel

#### ✔ Gestão de Tarefas
- Organização do trabalho do grupo
- Distribuição de responsabilidades
- (Inserir screenshot da plataforma usada: Trello / Notion / etc.)

---

# 🔄 Fluxo do Sistema

1. Utilizador insere descrição no frontend
2. Frontend envia request para o backend
3. Backend:
   - Processa com IA
   - Guarda na base de dados
   - Gera projeto Laravel
4. Sistema cria ZIP do backend
5. Frontend mostra resultado e permite download

---

# ✅ Validação Final do Sistema

O sistema cumpre todos os requisitos:

- ✔ Recebe dados (input do utilizador)
- ✔ Processa os dados (IA + backend)
- ✔ Guarda na base de dados
- ✔ Envia dados para o frontend
- ✔ Mostra os dados na interface
- ✔ Permite download do resultado

---

# 📦 Publicação

O projeto foi publicado no GitHub conforme solicitado.
