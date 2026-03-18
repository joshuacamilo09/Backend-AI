# 📄 Relatório Individual — Projeto BackendAI

## 👥 Elementos do Grupo
- Joshua  
- Filomeno  
- Alex  

---

# 📌 Divisão de Tarefas

## 🧠 Joshua — Backend (Base de Dados + Servidor)

### Responsabilidades:
- Desenvolvimento do backend em Laravel  
- Ligação entre base de dados, servidor e frontend  
- Implementação das APIs  
- Integração com IA para geração de backends  
- Estruturação da base de dados  

### 🔗 Backend: Ligação Base de Dados - Servidor - HTML

#### Base de Dados:
Foram criadas as seguintes tabelas:
- **projects** → guarda os projetos criados  
- **specifications** → guarda o output da IA  
- **generations** → guarda cada backend gerado  

#### Servidor:
O backend:
- Recebe dados do frontend (descrição do projeto)
- Processa com IA
- Guarda na base de dados
- Gera o backend automaticamente
- Retorna resposta JSON

#### Ligação ao HTML:
- O servidor expõe endpoints (`/api/...`)
- O frontend consome esses dados via `fetch`
- Os dados são exibidos dinamicamente nas páginas

---

## 🎨 Filomeno — Frontend (UX/UI + Estrutura)

### Responsabilidades:
- Desenvolvimento das páginas HTML  
- Estrutura do dashboard  
- Experiência do utilizador (UX)  
- Layout e organização da interface  

---

### 🧩 Frontend: User Tasks

Principais tarefas do utilizador:

1. Fazer login / registo  
2. Aceder ao dashboard  
3. Criar um novo backend  
4. Visualizar projetos  
5. Fazer download do backend gerado  

---

### 🔄 User Flow (Fluxo do Utilizador)

1. Utilizador entra na plataforma  
2. Faz login ou registo  
3. Acede ao dashboard  
4. Vai para "Generate Backend"  
5. Escreve descrição  
6. Clica em "Generate"  
7. Backend processa  
8. Resultado aparece no ecrã  
9. Utilizador faz download  

---

### 📊 Fluxograma (Lógico do Sistema)

- Input do utilizador  
→ Envio para backend  
→ Processamento com IA  
→ Guardar na base de dados  
→ Gerar backend  
→ Enviar resposta  
→ Mostrar no frontend  

---

### 🧱 Wireframes

Foram definidos wireframes para:

- Dashboard  
- Generate Backend  
- Projects  
- Templates  
- Documentation  
- Settings  

Os wireframes seguem uma estrutura comum:
- Sidebar de navegação  
- Topbar com ações principais  
- Área de conteúdo com cards/tabelas  

---

## 🔗 Alex — Integração e Testes

### Responsabilidades:
- Integração entre frontend e backend  
- Testes do sistema  
- Validação do fluxo completo  
- Correção de erros  
- Organização do projeto  

---

### ✅ Validação do Sistema

O sistema foi testado garantindo que:

- ✔ O frontend envia dados corretamente  
- ✔ O backend recebe e processa  
- ✔ Os dados são guardados na base de dados  
- ✔ O backend retorna dados corretamente  
- ✔ O frontend exibe os dados  

---

### 🔄 Fluxo Completo Validado

1. Utilizador escreve descrição  
2. Frontend envia request  
3. Backend processa com IA  
4. Dados são guardados  
5. Backend gera ZIP  
6. Frontend mostra resultado  
7. Utilizador faz download  

---

# 📦 Conclusão

O sistema BackendAI cumpre todos os requisitos:

- Integração completa entre frontend e backend  
- Ligação funcional com base de dados  
- Interface funcional e intuitiva  
- Fluxo de utilizador claro  
- Geração automática de backends  
