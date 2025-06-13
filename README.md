# Página de Venda (Painel de Controle)

Este projeto é uma aplicação web construída com **Laravel 12**, utilizando **Breeze** com **Inertia.js** e **Vue.js** para o front-end. A aplicação oferece um painel de controle para gerenciar vendas de forma prática e moderna.

---

## 🚀 Tecnologias Utilizadas

- **Laravel 12** – Framework PHP moderno e robusto
- **Laravel Breeze** – Autenticação leve e simples para Laravel
- **Inertia.js** – Ponte entre Laravel e Vue.js
- **Vue.js 3** – Framework JavaScript progressivo

---

## ⚙️ Funcionalidades Principais

- Autenticação com Laravel Breeze
- Painel de controle integrado com Vue.js via Inertia
- Sistema de vendas com suporte a CRUD de produtos, pedidos, e clientes
- Integração com banco de dados via Migrations e Factories

---

## 🧩 Estrutura do Projeto

### Migrations

As migrations configuram as tabelas principais do banco de dados, como:

- `users` – Usuários do sistema
- `produto` – Produtos disponíveis para venda
- `venda` – Venda para controle
- `parcelas` – Venda para controle

### Factories

O projeto utiliza **Factories** para popular o banco com dados de teste, ideal para desenvolvimento e testes automáticos.

---

## ▶️ Como rodar o projeto

1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/seu-repo.git

2. Instale as dependências do PHP e do Node:
```bash
 composer install
npm install && npm run dev

3. Configure o .env e gere a chave da aplicação:
```bash
cp .env.example .env
php artisan key:generate


4. Rode as migrations e popule o banco: 
```bash
php artisan migrate --seed

5. Inicie o servidor:
```bash
php artisan serve
// ou 
use docker ./vendor/bin/sail up -d 
´´´


## Status dos Testes

Os testes foram configurados com PestPHP e cobrem autenticação,
 verificação de e-mail, atualização de perfil e acesso ao dashboard.

❗ Se algum teste falhar no CI (GitHub Actions),
 verifique se o ambiente está corretamente configurado com um
 `.env.testing` que aponte
para o banco de dados correto (SQLite in-memory ou arquivo).










