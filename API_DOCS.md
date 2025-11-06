# **Documentação da API \- Desafio Livraria**

Esta é a documentação para a API RESTful do sistema de livraria.

Base URL: http://localhost/api  
Autenticação: Nenhuma (para este desafio).

## **⚡ Teste Rápido (Postman/Hoppscotch)**

Para facilitar os testes, importe a coleção completa do Postman/Hoppscotch disponível na raiz deste projeto:

livraria-api.json

A coleção já inclui todos os *endpoints* e *bodies* de exemplo.

## **📖 Padrões de Resposta**

A API segue padrões RESTful consistentes.

* **Sucesso (GET, PUT):** 200 OK  
  { "data": { ... } }

* **Criação (POST):** 201 Created  
  {  
      "message": "Autor cadastrado com sucesso\!",  
      "data": { ... }  
  }

* **Exclusão (DELETE):** 200 OK (com mensagem)  
  { "message": "Autor removido com sucesso." }

* **Recurso Não Encontrado (Erro 404):**  
  { "error": "Autor não encontrado." }

* **Erro de Validação (Erro 422):** (Mensagens em PT-BR)  
  {  
      "message": "O campo Título é obrigatório.",  
      "errors": {  
          "Titulo": \[  
              "O campo Título é obrigatório."  
          \]  
      }  
  }

* **Conflito de Negócio (Erro 409):** (Ex: Deletar autor com livros)  
  { "error": "Este autor não pode ser excluído, pois está associado a livros." }

## **📚 Endpoints**

### **1\. Autores (/autores)**

* GET /autores: Lista (paginada) de autores.  
  * Query Param: ?busca=nome  
* POST /autores: Cria um novo autor.  
  * Body: { "Nome": "J.R.R. Tolkien" }  
* GET /autores/{id}: Obtém um autor específico.  
* PUT /autores/{id}: Atualiza um autor.  
  * Body: { "Nome": "Tolkien" }  
* DELETE /autores/{id}: Exclui um autor.

### **2\. Assuntos (/assuntos)**

* GET /assuntos: Lista (paginada) de assuntos.  
  * Query Param: ?busca=descricao  
* POST /assuntos: Cria um novo assunto.  
  * Body: { "Descricao": "Ficção" }  
* GET /assuntos/{id}: Obtém um assunto específico.  
* PUT /assuntos/{id}: Atualiza um assunto.  
  * Body: { "Descricao": "Ficção Científica" }  
* DELETE /assuntos/{id}: Exclui um assunto.

### **3\. Livros (/livros)**

* GET /livros: Lista (paginada) de livros (inclui autores e assuntos).  
  * Query Param: ?busca=titulo  
* POST /livros: Cria um novo livro e associa autores/assuntos.  
* GET /livros/{id}: Obtém um livro específico (inclui autores e assuntos).  
* PUT /livros/{id}: Atualiza um livro e sincroniza autores/assuntos.  
* DELETE /livros/{id}: Exclui um livro.

#### **Exemplo de Body (POST /livros)**

*(IDs de autores e assuntos devem existir no banco)*

{  
    "Titulo": "O Hobbit",  
    "Editora": "HarperCollins",  
    "Edicao": 1,  
    "AnoPublicacao": "1937",  
    "Valor": 59.90,  
    "autores": \[1, 2\],  
    "assuntos": \[1\]  
}  
