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

Siga este passo a passo para executar o projeto em seu ambiente local de forma rápida e fácil.

### Pré-requisitos

  * PHP (versão compatível com o Laravel do projeto, ex: 8.2+)
  * Composer
  * Node.js e NPM

### Instalação com um único comando

Para simplificar a instalação, foi criado um script que executa todos os passos necessários para configurar e iniciar a aplicação.

1.  **Clone o Repositório:**
    ```bash
    git clone https://github.com/fernandes-azevedo/livraria.git
    cd livraria
    ```

2.  **Execute o comando de start:**
    Abra o terminal (Prompt de Comando, PowerShell, ou qualquer outro) na raiz do projeto e execute o seguinte comando:
    ```bash
    composer start
    ```

### O que o `composer start` faz?

Este comando executa o script `start.php` que automatiza as seguintes tarefas:

1.  **Instala as dependências do PHP:** Roda `composer install` (se necessário).
2.  **Configura o ambiente:** Cria o arquivo `.env` a partir do `.env.example` (se necessário).
3.  **Gera a chave da aplicação:** Roda `php artisan key:generate` (se necessário).
4.  **Cria o banco de dados:** Cria o arquivo `database/database.sqlite` (se necessário).
5.  **Executa as migrations e o seeder:** Roda `php artisan migrate:fresh --seed` para criar as tabelas e popular o banco com dados de exemplo.
6.  **Instala as dependências do Frontend:** Roda `npm install` (se necessário).
7.  **Inicia os servidores:** Roda `npm start`, que por sua vez inicia o servidor de desenvolvimento do Vite e o servidor do Laravel (`php artisan serve`) em paralelo.

Após a execução do comando, a aplicação estará disponível em:
**[http://127.0.0.1:8000](http://127.0.0.1:8000)**

### O que Testar:

1.  Navegue pelos menus **Autores**, **Assuntos** e **Livros**.
2.  Teste o CRUD completo (Criar, Editar, Listar, Excluir) para cada uma das 3 entidades.
3.  Tente excluir um Autor que está associado a um Livro (o sistema deve impedir).
4.  Tente cadastrar um Autor com um nome que já existe  (o sistema deve impedir).
5.  Clique no link **Relatório** na barra de navegação. Um PDF agrupado por autor deve ser gerado e aberto no navegador.

### Como Rodar os Testes

Para executar a suíte de testes automatizados do projeto, utilize o seguinte comando no seu terminal:

```bash
composer test
```

Este comando irá executar os testes de feature e de unidade, garantindo que as funcionalidades principais da aplicação estão operando como esperado.