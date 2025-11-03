# Desafio Técnico: Cadastro de Livros (Livraria)

Este projeto é a implementação de um desafio técnico para uma vaga de Desenvolvedor PHP. O objetivo é criar um sistema de CRUD (Create, Read, Update, Delete) para gerenciar Livros, Autores e Assuntos, seguindo um modelo de dados específico e aplicando as melhores práticas de desenvolvimento de software.

O sistema foi construído em poucas horas, com foco em demonstrar proficiência em **SOLID**, **Clean Code**, **TDD** (Test-Driven Development) e domínio do ecossistema **Laravel**.

## Tecnologias Utilizadas

  * **Framework:** Laravel 12
  * **Banco de Dados:** SQLite (para agilidade e portabilidade)
  * **Frontend:** Bootstrap 5 (via `laravel/ui` e Vite)
  * **Relatórios:** `barryvdh/laravel-dompdf` (para geração de PDF)
  * **Testes:** PHPUnit

## Destaques da Implementação e Boas Práticas

Este não é apenas um CRUD simples. A estrutura foi pensada para demonstrar uma arquitetura robusta e escalável, mesmo em um projeto pequeno:

1.  **Mapeamento de Schema Legado (ORM Avançado):**

      * O desafio exigia seguir um Modelo Entidade-Relacionamento (ERD) existente (ex: `CodAu`, `Livro_Autor`, `codAs`).
      * Demonstrei como mapear o Eloquent (Active Record) para esse schema "legado", configurando `protected $table`, `protected $primaryKey`, `public $timestamps = false` e os nomes de chaves em relacionamentos `belongsToMany()`. Isso prova a capacidade de integrar o Laravel a sistemas existentes.

2.  **SOLID - Princípio da Responsabilidade Única (SRP):**

      * **Form Requests:** Toda a lógica de validação de criação (`Store...Request`) e atualização (`Update...Request`) foi extraída dos controllers. O controller nem "sabe" quais são as regras; ele apenas recebe os dados já validados (`$request->validated()`), mantendo-se "magro" (skinny controller).
      * **Exemplo:** `app/Http/Requests/StoreLivroRequest.php`

3.  **SOLID - Princípio da Inversão de Dependência (DIP):**

      * Os controllers dependem de abstrações (os `Form Requests` específicos) em vez de implementações concretas (o `Request` genérico). Isso torna o código mais limpo e facilita os testes.

4.  **TDD (Test-Driven Development):**

      * O CRUD de Autores foi guiado por testes de feature (`tests/Feature/AutorFeatureTest.php`). Os testes foram escritos *antes* do código de implementação para validar o "happy path" (criação com sucesso) e o "sad path" (falha de validação).

5.  **Boas Práticas de Banco de Dados:**

      * **Transações (`DB::transaction`):** As operações de `store` e `update` do `Livro` são complexas (mexem em 3 tabelas). Elas foram encapsuladas em transações para garantir a integridade atômica dos dados (ou tudo é salvo, ou nada é).
      * **Evitando N+1 Query:** Na listagem de Livros (`LivroController@index`), utilizei **Eager Loading** (`Livro::with('autores', 'assuntos')`) para carregar os relacionamentos em uma única consulta, otimizando drasticamente a performance.
      * **Migrations para `VIEW`s:** O relatório obrigatório é gerado a partir de uma `VIEW` (`view_relatorio_livros_autores`) que foi criada via Migration, mantendo toda a estrutura do banco versionada.

6.  **Tratamento de Erros Específico:**

      * Conforme solicitado no desafio, o sistema evita `try-catch` genéricos. No `AutorController@store`, por exemplo, há um `catch` específico para `QueryException` que verifica o código de erro de violação de `UNIQUE constraint`, retornando uma mensagem amigável ao usuário.
      * **Proteção de Deleção:** O sistema impede a exclusão de Autores ou Assuntos que possuam livros associados, protegendo a integridade referencial.

7.  **Segurança e Padrões Web:**

      * Uso de `@csrf` em todos os formulários.
      * Proteção de `Mass Assignment` (`$fillable`) em todos os Models.
      * Uso correto de verbos HTTP (formulários de exclusão usam `@method('DELETE')` em vez de links `GET`).

-----

## Como Inicializar e Testar o Projeto

Siga este passo a passo para executar o projeto em seu ambiente local.

### Pré-requisitos

  * PHP (versão compatível com o Laravel do projeto, ex: 8.2+)
  * Composer
  * Node.js e NPM

### Passo 1: Clonar o Repositório

```bash
git clone https://github.com/fernandes-azevedo/livraria.git
cd [livraria]
```

### Passo 2: Instalar Dependências do PHP

```bash
composer install
```

### Passo 3: Configurar o Ambiente

Copie o arquivo de ambiente de exemplo.

```bash
cp .env.example .env
```

*(No Windows, se `cp` falhar, use: `copy .env.example .env`)*

### Passo 4: Gerar a Chave da Aplicação

```bash
php artisan key:generate
```

### Passo 5: Criar o Banco de Dados (SQLite)

Este projeto usa SQLite para facilitar a configuração. Você só precisa criar o arquivo em branco:

**No macOS ou Linux:**

```bash
touch database/database.sqlite
```

**No Windows (PowerShell):**

```bash
New-Item -ItemType File database\database.sqlite
```

*(Ou crie um arquivo vazio chamado `database.sqlite` manualmente dentro da pasta `database`)*

### Passo 6: Rodar as Migrations (Criar Tabelas e View)

Usamos `migrate:fresh` para garantir que o banco seja recriado do zero e a `VIEW` do relatório seja incluída corretamente.

```bash
php artisan migrate:fresh
```

### Passo 6.1: Popular o banco de dados com dados de exemplo

Para preencher o banco de dados com dados de exemplo (50 autores, 50 assuntos e 50 livros), execute o seguinte comando:

```bash
php artisan db:seed
```

### Passo 7: Instalar Dependências do Frontend

```bash
npm install
```

### Passo 8: Compilar os Assets (Bootstrap)

Mantenha este comando rodando em um terminal separado para compilar o CSS/JS enquanto navega.

```bash
npm run dev
```

### Passo 9: Iniciar o Servidor

Em outro terminal, inicie o servidor local do Laravel.

```bash
php artisan serve
```

### Passo 10: Acessar a Aplicação

Pronto\! A aplicação estará disponível em:
**[http://127.0.0.1:8000](http://127.0.0.1:8000)**

### O que Testar:

1.  Navegue pelos menus **Autores**, **Assuntos** e **Livros**.
2.  Teste o CRUD completo (Criar, Editar, Listar, Excluir) para cada uma das 3 entidades.
3.  Tente excluir um Autor que está associado a um Livro (o sistema deve impedir).
4.  Tente cadastrar um Autor com um nome que já existe (o sistema deve retornar o erro de `UNIQUE`).
5.  Clique no link **Relatório** na barra de navegação. Um PDF agrupado por autor deve ser gerado e aberto no navegador.