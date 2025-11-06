# Desafio Técnico: Cadastro de Livros (Livraria)

Este projeto é a implementação de um desafio técnico para uma vaga de Desenvolvedor PHP. O objetivo é criar um sistema de CRUD (Create, Read, Update, Delete) para gerenciar Livros, Autores e Assuntos, seguindo um modelo de dados específico e aplicando as melhores práticas de desenvolvimento de software.

O sistema foi construído em poucas horas, com foco em demonstrar proficiência em **SOLID**, **Clean Code**, **TDD** (Test-Driven Development) e domínio do ecossistema **Laravel**.

## Tecnologias Utilizadas

  * **Framework:** Laravel 12
  * **Banco de Dados:** SQLite (para agilidade e portabilidade)
  * **Busca:** Laravel Scout (com driver `database`)
  * **Frontend:** Bootstrap 5 (via `laravel/ui` e Vite)
  * **Relatórios:** `barryvdh/laravel-dompdf` (para geração de PDF)
  * **Testes:** PHPUnit
  * **Cache:** Driver de Cache padrão do Laravel (File)

## Destaques da Implementação e Boas Práticas

Este não é apenas um CRUD simples. A estrutura foi pensada para demonstrar uma arquitetura robusta e escalável, mesmo em um projeto pequeno:

1.  **Mapeamento de Schema Legado (ORM Avançado):**

      * O desafio exigia seguir um Modelo Entidade-Relacionamento (ERD) existente (ex: `CodAu`, `Livro_Autor`, `codAs`).
      * Demonstrei como mapear o Eloquent (Active Record) para esse schema "legado", configurando `protected $table`, `protected $primaryKey`, `public $timestamps = false` e os nomes de chaves em relacionamentos `belongsToMany()`. Isso prova a capacidade de integrar o Laravel a sistemas existentes.

2.  **Otimização de Performance (Paginação, Cache e Busca):**

      * **Paginação:** Todas as listagens (`index`) foram refatoradas de `::get()` para `::paginate(15)`, garantindo que o sistema escale sem sobrecarregar o servidor. Os links de paginação preservam a busca ativa.
      * **Busca (Laravel Scout):** Foi implementado o Laravel Scout com o driver `database` para permitir busca full-text otimizada (ex: `Autor::search(...)`) em vez de `WHERE LIKE`s lentos.
      * **Caching (`Cache::remember`):** As consultas recorrentes (como as listas de Autores e Assuntos nos formulários de Livros) são cacheadas para reduzir drasticamente as consultas ao banco de dados, melhorando a performance geral da aplicação.

3.  **SOLID - Princípio da Responsabilidade Única (SRP):**

      * **Form Requests:** Toda a lógica de validação de criação (`Store...Request`) e atualização (`Update...Request`) foi extraída dos controllers. O controller nem "sabe" quais são as regras; ele apenas recebe os dados já validados (`$request->validated()`), mantendo-se "magro" (skinny controller).
      * **Exemplo:** `app/Http/Requests/StoreLivroRequest.php`

4.  **SOLID - Princípio da Inversão de Dependência (DIP):**

      * Os controllers dependem de abstrações (os `Form Requests` específicos) em vez de implementações concretas (o `Request` genérico). Isso torna o código mais limpo e facilita os testes.

5.  **TDD (Test-Driven Development):**

      * O CRUD de Autores foi guiado por testes de feature (`tests/Feature/AutorFeatureTest.php`). Os testes foram escritos *antes* do código de implementação para validar o "happy path" (criação com sucesso) e o "sad path" (falha de validação).

6.  **Boas Práticas de Banco de Dados:**

      * **Transações (`DB::transaction`):** As operações de `store` e `update` do `Livro` são complexas (mexem em 3 tabelas). Elas foram encapsuladas em transações para garantir a integridade atômica dos dados (ou tudo é salvo, ou nada é).
      * **Evitando N+1 Query:** Na listagem de Livros (`LivroController@index`), utilizei **Eager Loading** (`Livro::with('autores', 'assuntos')`) para carregar os relacionamentos em uma única consulta, otimizando drasticamente a performance.
      * **Migrations para `VIEW`s:** O relatório obrigatório é gerado a partir de uma `VIEW` (`view_relatorio_livros_autores`) que foi criada via Migration, mantendo toda a estrutura do banco versionada.

7.  **Tratamento de Erros Específico:**

      * Conforme solicitado no desafio, o sistema evita `try-catch` genéricos. No `AutorController@store`, por exemplo, há um `catch` específico para `QueryException` que verifica o código de erro de violação de `UNIQUE constraint`, retornando uma mensagem amigável ao usuário.
      * **Proteção de Deleção:** O sistema impede a exclusão de Autores ou Assuntos que possuam livros associados, protegendo a integridade referencial.

8.  **Segurança e Padrões Web:**

      * Uso de `@csrf` em todos os formulários.
      * Proteção de `Mass Assignment` (`$fillable`) em todos os Models.
      * Uso correto de verbos HTTP (formulários de exclusão usam `@method('DELETE')` em vez de links `GET`).

-----

## **Como Inicializar e Testar o Projeto (com Docker)**

Este projeto é 100% containerizado usando Docker. A instalação é totalmente automatizada e requer **apenas Git e Docker** na máquina local.

### **Pré-requisitos**

* Docker e Docker Compose (Docker Desktop)  
* Git  
* *(No Windows, é necessário o Git Bash para executar o script .sh)*

### **Instalação Automatizada (O Único Comando)**

Para facilitar ao máximo a avaliação, basta clonar o projeto e executar o script de setup.

1. **Clone o Repositório:**  
   git clone \[https://github.com/fernandes-azevedo/livraria.git\](https://github.com/fernandes-azevedo/livraria.git)  
   cd livraria

2. **Dê Permissão de Execução ao Script** (Apenas macOS/Linux):  
   chmod \+x start.sh

3. Execute o Comando de Start:  
   Este comando orquestra todo o setup do Docker, instalações, migrações e compilação de assets:  
   ./start.sh

   *(No Git Bash do Windows, talvez seja necessário usar: bash start.sh)*

### **O que o start.sh faz?**

Este script automatiza as seguintes tarefas:

1. **Verifica o .env:** Cria o .env a partir do .env.example.  
2. **Inicia o Docker:** Executa docker compose up \-d \--build para subir os contêineres (nginx, app, mysql, redis, node).  
3. **Instala Dependências:** Executa composer install *dentro* do contêiner app.  
4. **Configura o Laravel:** Gera a APP\_KEY e limpa os caches.  
5. **Prepara o Banco de Dados:** Executa migrate:fresh (criando tabelas, views, triggers e procedures).  
6. **Compila o Frontend:** Executa npm install e npm run build *dentro* do contêiner node.  
7. **Indexa a Busca:** Executa o scout:import para popular o índice de busca.

Após a execução (pode levar alguns minutos na primeira vez), a aplicação estará pronta e disponível em:

[**http://localhost**](https://www.google.com/search?q=http://localhost)

### **O que Testar:**

1. Acesse a aplicação web em http://localhost.  
2. Use o **Frontend (Blade)**: Navegue pelos menus, crie, edite e exclua livros, autores e assuntos.  
3. Use a **Busca (Scout)**: Utilize o campo de busca em cada listagem.  
4. Use o **Relatório PDF**: Clique no link "Relatório" para gerar o PDF (que usa a VIEW do banco).  
5. Teste a **API (RESTful)**:  
   * Use um arquivo de coleção do Postman/Hoppscotch ou faça uma requisição manual:  
     \# Teste de listagem  
     curl \-H "Accept: application/json" "http://localhost/api/livros"

     \# Teste de 404 (Model Not Found)  
     curl \-H "Accept: application/json" "http://localhost/api/livros/999"

6. Teste a **Trigger de Auditoria** (Opcional):  
   * Crie um novo livro pela UI ou API.  
   * Verifique o log no banco de dados (ex: via Tinker ou um cliente de BD): docker compose exec app php artisan tinker \--execute="print\_r(DB::table('audit\_log')-\>get())".  
7. Teste a **Stored Procedure** (Opcional):  
   * docker compose exec app php artisan tinker \--execute="print\_r(DB::select('CALL sp\_GetDashboardStats()'))".

### **Como Rodar os Testes**

Para executar a suíte de testes automatizados (PHPUnit), utilize o seguinte comando:

docker compose exec app php artisan test  
