# Relatório Individual — Fase II: Ideação  
## Projeto: BackendAI  
**Unidade Curricular:** Projeto Desenvolvimento Web  
**Professora da UC:** Maria Pires

**Curso:** Engenharia Informática  
**Ano:** 2.º Ano  

---

# 1. Introdução

Este relatório descreve a fase de **Ideação** do projeto **BackendAI**, desenvolvido em grupo no âmbito da unidade curricular de Desenvolvimento Web.

O BackendAI é uma plataforma web pensada para ajudar programadores, estudantes e empreendedores a **gerar automaticamente a estrutura base de backends**, a partir de uma descrição em linguagem natural. A ideia principal do sistema é reduzir o tempo gasto na configuração inicial de projetos backend, automatizando tarefas repetitivas e oferecendo uma base pronta para desenvolvimento.

Este documento apresenta a **descrição da solução**, as **funcionalidades principais e secundárias**, bem como a **arquitetura de informação**, o **mapa de navegação** e a respetiva **validação através de tree testing**, tal como solicitado na Fase II do projeto.

---

# 2. Descrição da Solução

## 2.1 Visão Geral

O **BackendAI** é uma plataforma web que permite ao utilizador descrever, em texto, o backend que pretende criar (por exemplo, “quero uma API CRUD para gestão de livraria com autenticação e documentação”), e receber como resultado uma estrutura inicial de backend preparada para desenvolvimento.

A solução pretende resolver um problema comum no desenvolvimento de software: o tempo e esforço gastos a criar a base técnica de um projeto, incluindo estrutura de pastas, configuração inicial, entidades, autenticação, rotas e organização geral.

Em vez de começar do zero, o utilizador pode usar a plataforma para:

- gerar um backend inicial automaticamente;
- visualizar os projetos criados;
- descarregar o projeto em formato ZIP;
- consultar templates e documentação;
- gerir a sua conta.

## 2.2 Objetivo Principal

O principal objetivo da aplicação é:

> **Permitir que utilizadores gerem a base estrutural de um backend de forma rápida, simples e organizada, através de uma interface web intuitiva.**

Ou seja, o foco do sistema não é apenas “mostrar informação”, mas **transformar uma ideia numa base técnica utilizável**.

---

# 3. Funcionalidades do Sistema

Segundo a estrutura sugerida na Fase II, as funcionalidades devem ser divididas entre:

- **Core Features** (funcionalidades essenciais)
- **Nice-to-haves** (funcionalidades secundárias)

---

# 4. Core Features (Funcionalidades Principais)

As **core features** são as funcionalidades sem as quais o sistema deixaria de cumprir o seu propósito principal.

## 4.1 Autenticação de Utilizador

A plataforma inclui um sistema de autenticação para garantir que cada utilizador tem acesso apenas aos seus próprios projetos e dados.

### Inclui:
- Registo de conta
- Login
- Logout
- Recuperação de password
- Verificação de email
- Gestão de perfil

### Importância:
Esta funcionalidade é essencial porque o sistema precisa de associar cada geração e cada projeto a um utilizador autenticado.

---

## 4.2 Geração de Backend por Prompt

Esta é a funcionalidade central do BackendAI.

O utilizador introduz uma descrição textual do backend que pretende gerar, por exemplo:

> “Quero uma API CRUD para gestão de livraria com autenticação e documentação.”

A plataforma envia esse pedido para o backend, processa a descrição e gera uma estrutura base de projeto.

### Importância:
Sem esta funcionalidade, o sistema perde completamente a sua proposta de valor principal.

---

## 4.3 Armazenamento dos Projetos Gerados

Depois de uma geração ser concluída, o sistema guarda o projeto e os respetivos metadados na base de dados.

### Exemplos de dados armazenados:
- nome do projeto
- descrição
- framework usada
- data de criação
- estado da geração
- associação ao utilizador

### Importância:
Permite persistência e histórico, tornando a plataforma útil além de uma única sessão.

---

## 4.4 Página de Projetos

A aplicação disponibiliza uma página onde o utilizador pode consultar todos os projetos que já gerou.

### Funções principais:
- listar projetos
- visualizar estado da geração
- consultar framework
- descarregar o ZIP gerado

### Importância:
Esta funcionalidade dá continuidade ao uso da plataforma e melhora a organização do utilizador.

---

## 4.5 Dashboard com Resumo de Atividade

O sistema inclui um dashboard inicial com informação resumida sobre a atividade do utilizador.

### Exemplos:
- total de projetos
- total de gerações
- última atividade
- framework mais usada

### Importância:
Melhora a experiência do utilizador e dá contexto imediato sobre a sua utilização da plataforma.

---

## 4.6 Ligação Base de Dados → Servidor → HTML

Esta funcionalidade demonstra a validação técnica pedida no projeto:

- os dados entram no sistema;
- são guardados na base de dados;
- são lidos pelo servidor;
- e apresentados no frontend HTML.

### Exemplo concreto no BackendAI:
1. O utilizador cria um backend.
2. O backend recebe o pedido.
3. O sistema guarda o projeto e a geração na base de dados.
4. O frontend vai buscar os dados via API.
5. A página HTML mostra os projetos e o estado da geração.

### Importância:
Esta funcionalidade valida o funcionamento completo do sistema.

---

# 5. Nice-to-Haves (Funcionalidades Secundárias)

Estas funcionalidades não são obrigatórias para o sistema funcionar, mas aumentam bastante o valor e a qualidade da experiência.

## 5.1 Templates Pré-definidos

A plataforma inclui uma secção de templates que ajuda o utilizador a começar mais rapidamente.

### Exemplos:
- E-Commerce API
- Blog Platform
- CRM System
- SaaS Starter
- Chat Application
- Task Manager

### Vantagem:
Permite reduzir o esforço do utilizador e acelerar ainda mais o processo de geração.

---

## 5.2 Secção de Documentação

A plataforma possui uma área de documentação para ajudar o utilizador a compreender como usar o sistema.

### Exemplos de tópicos:
- Getting Started
- Prompt Guide
- Framework Support
- API Reference
- Authentication
- Deployment

### Vantagem:
Melhora onboarding, reduz dúvidas e aumenta autonomia do utilizador.

---

## 5.3 Página de Settings

A secção de definições permite ao utilizador gerir a sua conta.

### Exemplos:
- ver nome e email
- editar perfil
- alterar password
- logout

### Vantagem:
Melhora controlo da conta e consistência da plataforma.

---

## 5.4 Pesquisa de Projetos

Na área de dashboard e projetos existe um campo de pesquisa para filtrar rapidamente projetos.

### Vantagem:
Facilita a navegação quando o utilizador já tem vários projetos criados.

---

## 5.5 Feedback Visual de Estado

A plataforma apresenta estados visuais como:

- **Generating**
- **Completed**
- **Error**
- **Pending**

### Vantagem:
Ajuda o utilizador a perceber o que está a acontecer no sistema em tempo real.

---

# 6. Arquitetura de Informação

A arquitetura de informação organiza o conteúdo e as funcionalidades do sistema de forma lógica e intuitiva.

De acordo com a abordagem apresentada na unidade curricular, a arquitetura de informação deve ter em conta três componentes principais:

- **Users** → quem usa o sistema
- **Context** → em que contexto usam
- **Content** → que conteúdo precisam de encontrar  
---

## 6.1 Users (Utilizadores)

Os principais utilizadores do BackendAI são:

- estudantes de programação
- programadores backend
- freelancers
- empreendedores digitais
- utilizadores técnicos que precisam de acelerar a criação de APIs e estruturas backend

### Necessidades principais:
- gerar projetos rapidamente
- organizar projetos criados
- descarregar código base
- aceder a templates
- perceber como usar a plataforma

---

## 6.2 Context (Contexto de Utilização)

O sistema é utilizado em contexto de desenvolvimento de software, especialmente em fases iniciais de criação de produto.

### Exemplos de contexto:
- criação de MVPs
- prototipagem técnica
- aprendizagem de backend
- aceleração de desenvolvimento
- testes de ideias de produto

---

## 6.3 Content (Conteúdo)

O conteúdo principal da plataforma inclui:

- conta do utilizador
- projetos
- gerações
- prompts de backend
- ficheiros ZIP
- templates
- documentação
- definições da conta

Este conteúdo foi organizado de forma a tornar a navegação clara e previsível.

---

# 7. Estrutura do Conteúdo

Para organizar a aplicação, o conteúdo foi agrupado em secções principais.

## 7.1 Conteúdo Principal

- Dashboard
- Generate Backend
- Projects
- Templates
- Documentation
- Settings

## 7.2 Conteúdo Secundário

- Login
- Registo
- Forgot Password
- Reset Password
- Verify Email
- Edit Profile

---

# 8. Mapa de Navegação

O mapa de navegação representa a hierarquia e a estrutura do sistema.

## 8.1 Estrutura Principal

```text
HOME / LANDING
│
├── Login
├── Register
│
└── Área Autenticada
    │
    ├── Dashboard
    │
    ├── Generate Backend
    │
    ├── Projects
    │   ├── Lista de Projetos
    │   └── Download ZIP
    │
    ├── Templates
    │   ├── E-Commerce API
    │   ├── Blog Platform
    │   ├── CRM System
    │   ├── SaaS Starter
    │   ├── Chat Application
    │   └── Task Manager
    │
    ├── Documentation
    │   ├── Getting Started
    │   ├── Prompt Guide
    │   ├── Framework Support
    │   ├── API Reference
    │   ├── Authentication
    │   └── Deployment
    │
    ├── Settings
    │   ├── User Information
    │   ├── Security
    │   └── Logout
    │
    └── Profile
        ├── Edit Profile
        ├── Update Password
        └── Delete Account
