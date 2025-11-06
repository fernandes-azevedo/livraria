# **Desafio Técnico: API e Aplicação de Livraria**

Este projeto é a implementação de um desafio técnico para uma vaga de Desenvolvedor PHP. O objetivo é demonstrar o domínio de tecnologias modernas e uma arquitetura robusta, indo além de um simples CRUD para entregar um ecossistema de aplicação completo.

O sistema é composto por:

1. Um **Frontend** para gerenciamento.  
2. Uma **API RESTful** completa e documentada.  
3. Um **Ambiente Docker** containerizado para fácil execução.

## **🚀 Atendendo aos Requisitos do Desafio**

Este projeto foi estruturado para atender especificamente aos critérios de senioridade exigidos:

| Requisito | Como foi Atendido |
| :---- | :---- |
| ✅ **Utilização de Docker** | O projeto é 100% containerizado com docker-compose.yml, incluindo nginx, app (PHP-FPM), mysql, redis e node. |
| ✅ **Frameworks (Produtividade)** | Utilizamos **Laravel 12**, o framework líder em produtividade no ecossistema PHP. |
| ✅ **Backlog Estruturado** | A arquitetura é dividida em camadas claras: **Models** (ORM), **View** (Blade), **Controllers**, , **Api\\Controllers** (API), **Resources** (Transformação JSON) e **Requests** (Validação). |
| ✅ **APIs... documentadas** | Foi desenvolvida uma API RESTful completa. A documentação está no arquivo [**API\_DOCS.md**](https://github.com/fernandes-azevedo/livraria/blob/main/API_DOCS.md) e uma coleção do Postman (livraria-api.json) está disponível. |
| ✅ **Conhecimento em BD** | Implementamos **Tabelas** (com schema legado), **Views** (para relatórios), **Triggers** (para auditoria) e **Procedures** (para estatísticas). |
| 📌 **Relatório Técnico do BD** | Um relatório detalhado sobre a modelagem e o uso de Views/Triggers/Procedures está disponível em [**DB\_REPORT.md**](https://github.com/fernandes-azevedo/livraria/blob/main/DB_REPORT.md). |
| 🧪 **Testes de Qualidade** | O projeto inclui testes de feature (PHPUnit) para a API (ex: LivroApiTest.php), que rodam no banco de dados real (via RefreshDatabase) para garantir a integridade. |
| 📌 **Idioma (Português)** | O Laravel está configurado (APP\_LOCALE=pt\_BR) e todas as mensagens de erro de validação da API (Form Requests) retornam em português. |
| 📌 **Máscara (Monetária)** | A formatação de valores monetários (R$) foi aplicada no relatório PDF (number\_format). A API retorna o valor como float para o frontend formatar. |

## **🛠️ Tecnologias Utilizadas**

* **Framework:** Laravel 12  
* **Containerização:** Docker (com docker-compose.yml)  
* **Serviços:** Nginx, PHP 8.3-FPM, MySQL 8.0, Redis (para Cache, Sessões e Filas)  
* **Banco de Dados:** MySQL 8.0  
* **Busca:** Laravel Scout (com driver database)  
* **Frontend:** Bootstrap 5 (via laravel/ui e Vite)  
* **Relatórios:** barryvdh/laravel-dompdf (para geração de PDF)  
* **Testes:** PHPUnit

## **🏁 Como Inicializar e Testar o Projeto (com Docker)**

Este projeto é 100% containerizado. A instalação é automatizada e facilitada, requer **apenas Git e Docker** na máquina local.

### **Pré-requisitos**

* Docker e Docker Compose (Docker Desktop)  
* Git  
* *(No Windows, é necessário o Git Bash para executar o script .sh)*

### **Instalação Automatizada (O Único Comando)**

1. **Clone o Repositório:**  
   git clone \[https://github.com/fernandes-azevedo/livraria.git\](https://github.com/fernandes-azevedo/livraria.git)  
   cd livraria

2. **Dê Permissão de Execução ao Script** (Apenas macOS/Linux):  
   chmod \+x start.sh

3. Execute o Comando de Start:  
   Este script orquestra todo o setup do Docker, instalações, migrações e compilação de assets:  
   ./start.sh

   *(No Git Bash do Windows, talvez seja necessário usar: bash start.sh)*

### **O que o start.sh faz?**

Este script automatiza as seguintes tarefas:

1. **Verifica o .env:** Cria o .env a partir do .env.example.  
2. **Inicia o Docker:** Executa docker compose up \-d \--build para subir os contêineres (nginx, app, mysql, redis, node).  
3. **Instala Dependências:** Executa composer install *dentro* do contêiner app.  
4. **Configura o Laravel:** Gera a APP\_KEY e limpa os caches.  
5. **Prepara o Banco de Dados:** Executa migrate:fresh (criando tabelas, views, triggers e procedures).  

Após a execução (pode levar alguns minutos na primeira vez), a aplicação estará pronta e disponível em:

[**http://localhost**](http://localhost)

### **🧪 O que Testar**

1. Acesse a aplicação web em **http://localhost**.  
2. Use o **Frontend (Blade)**: Navegue pelos menus, crie, edite e exclua livros, autores e assuntos.  
3. Use a **Busca (Scout)**: Utilize o campo de busca em cada listagem.  
4. Use o **Relatório PDF**: Clique no link "Relatório" para gerar o PDF (que usa a VIEW do banco).  
5. Teste a **API (RESTful)**:  
   * **Importe** o arquivo livraria-api.json (na raiz do projeto) no Postman ou Hoppscotch.  
   * **OU** leia a documentação simples em [**API\_DOCS.md**](https://github.com/fernandes-azevedo/livraria/blob/main/API_DOCS.md).  
   * **OU** faça um teste rápido de 404:  
     curl \-H "Accept: application/json" "http://localhost/api/livros/999"

     *(Deve retornar {"error":"Recurso não encontrado (Livro)."})*  
6. Teste a **Trigger de Auditoria** (Opcional):  
   * Crie um novo livro pela UI ou API.  
   * Verifique o log no banco: docker compose exec app php artisan tinker \--execute="print\_r(DB::table('audit\_log')-\>get())"  
7. Teste a **Stored Procedure** (Opcional):  
   * docker compose exec app php artisan tinker \--execute="print\_r(DB::select('CALL sp\_GetDashboardStats()'))"

### **Como Rodar os Testes Automatizados**

Para executar a suíte de testes (PHPUnit), utilize o seguinte comando:

docker compose exec app php artisan test  
