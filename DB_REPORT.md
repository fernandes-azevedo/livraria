# **Relatório Técnico do Banco de Dados**

Este documento detalha as decisões de arquitetura e a implementação das funcionalidades avançadas de banco de dados, conforme solicitado no desafio.

**SGBD:** MySQL 8.0 (executando via Docker)

## **1\. Modelo de Dados (ERD) e Mapeamento (ORM)**

O desafio exigia seguir integralmente um ERD legado. Isso foi feito, e o Laravel Eloquent ORM foi configurado para mapear esse schema.

### **1.1. Mapeamento dos Models**

Para fazer o Eloquent funcionar com o schema legado (ex: CodAu em vez de id), as seguintes propriedades foram configuradas nos Models (Autor, Assunto, Livro):

* protected $table: Define o nome exato da tabela (ex: 'Autor').  
* protected $primaryKey: Define a chave primária customizada (ex: 'CodAu').  
* public $timestamps \= false: Desabilita os campos created\_at/updated\_at, que não existem no ERD.  
* protected $fillable: Define os nomes exatos das colunas (ex: 'Nome', 'Descricao').

### **1.2. Mapeamento de Relacionamentos N:N**

Para os relacionamentos "Muitos para Muitos" (ex: Livro\_Autor), foi necessário especificar todos os nomes de colunas e tabelas customizadas:

// Exemplo em App\\Models\\Livro.php  
public function autores()  
{  
    return $this-\>belongsToMany(  
        Autor::class,  
        'Livro\_Autor',    // Tabela pivot  
        'Livro\_CodI',     // Chave deste model na pivot  
        'Autor\_CodAu'     // Chave do outro model na pivot  
    );  
}

### **1.3. Mapeamento de Rotas (API)**

Para que o *Route Model Binding* da API funcionasse com chaves customizadas (ex: GET /api/autores/1), foi necessário usar o "Explicit Binding" no AppServiceProvider (ou bootstrap/app.php no Laravel 11+) para instruir o Laravel a usar firstOrFail() com a coluna correta, garantindo o retorno 404\.

## **2\. Funcionalidades Avançadas de BD (Versionadas)**

Todas as estruturas avançadas do banco de dados (View, Trigger, Procedure) foram criadas dentro de **Migrations do Laravel**, garantindo que o setup do projeto seja 100% automatizado (php artisan migrate).

### **2.1. VIEW: view\_relatorio\_livros\_autores**

* **Propósito:** Atender ao requisito do relatório PDF, que precisava de dados de 3 tabelas principais.  
* **O que faz:** Esta View (criada em ...\_create\_report\_livros\_por\_autor\_view.php) junta as tabelas Livro, Autor, Livro\_Autor e Livro\_Assunto.  
* **Feature Destaque:** Ela usa GROUP\_CONCAT(ass.Descricao SEPARATOR ', ') para agregar múltiplos assuntos em uma única string (ex: "Ficção, Fantasia"), simplificando a consulta no RelatorioController.

### **2.2. TRIGGER: trg\_livro\_after\_insert**

* **Propósito:** Atender ao requisito de Triggers, implementando uma trilha de auditoria básica.  
* **O que faz:** Esta Trigger (criada em ...\_create\_livro\_audit\_trigger.php) é disparada AFTER INSERT ON Livro.  
* **Feature Destaque:** Para cada novo livro inserido, a trigger automaticamente insere um registro na tabela audit\_log (também criada via migration), gravando a ação ("NOVO\_LIVRO"), o título e o ID do novo livro, e a data (NOW()). Isso garante a rastreabilidade no nível do banco de dados.

### **2.3. PROCEDURE: sp\_GetDashboardStats**

* **Propósito:** Atender ao requisito de Procedures e demonstrar otimização de consultas.  
* **O que faz:** Esta Stored Procedure (criada em ...\_create\_dashboard\_stats\_procedure.php) é designada para alimentar um futuro dashboard.  
* **Feature Destaque:** Ela executa 4 consultas de agregação (COUNT e AVG) em um único SELECT, retornando um *dataset* consolidado. Isso reduz a carga na aplicação e o tráfego de rede (network round-trip), pois a aplicação só precisa fazer uma chamada (CALL sp\_GetDashboardStats()) para obter todas as estatísticas de uma vez.