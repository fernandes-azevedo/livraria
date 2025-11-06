#!/bin/bash

# --- Funções Auxiliares de Saída ---
# Define cores
INFO='\033[34m[INFO]\033[0m'
SUCCESS='\033[32m[SUCCESS]\033[0m'
ERROR='\033[31m[ERROR]\033[0m'
CMD='\033[33m[CMD]\033[0m'

# Funções de Log
log_info() {
    echo -e "$INFO $1"
}
log_success() {
    echo -e "$SUCCESS $1"
}
log_error() {
    echo -e "$ERROR $1"
}
log_cmd() {
    echo -e "$CMD $ $1"
}
# ------------------------------------

# Função para executar um comando e parar em caso de erro
run_command() {
    log_cmd "$1"
    # Executa o comando. O 'set -e' no topo (implícito) pararia, 
    # mas vamos ser explícitos para clareza.
    if ! $1; then
        log_error "O comando falhou. Abortando o script."
        exit 1
    fi
}

# Função para verificar o .env
check_env() {
    if [ ! -f .env ]; then
        log_info "Arquivo '.env' não encontrado. Copiando de '.env.example'..."
        cp .env.example .env
        log_success "Arquivo '.env' criado com sucesso."
    else
        log_info "Arquivo '.env' já existe."
    fi
}

# --- Início da Execução ---
# Garante que o script pare se qualquer comando falhar
set -e

log_info "Iniciando o script de setup do Ambiente Docker..."

# 1. Verificar .env
check_env

# 2. Subir os contêineres Docker
log_info "Iniciando e construindo os contêineres Docker... (Isso pode levar alguns minutos na primeira vez)"
run_command "docker compose up -d --build"
log_success "Contêineres estão no ar!"

# 3. Aguardar o MySQL ficar pronto (importante!)
log_info "Aguardando o contêiner do MySQL (15 segundos)..."
sleep 15

# 4. Instalar dependências do Composer (dentro do contêiner)
log_info "Instalando dependências do Composer..."
run_command "docker compose exec app composer install --no-interaction --prefer-dist --optimize-autoloader"

# 5. Gerar a Chave da Aplicação
log_info "Gerando a chave da aplicação (APP_KEY)..."
run_command "docker compose exec app php artisan key:generate"

# 6. Limpar caches (para garantir que o .env seja lido)
log_info "Limpando caches de configuração..."
run_command "docker compose exec app php artisan optimize:clear"

# 7. Rodar as Migrações
log_info "Executando as migrações do banco de dados (Triggers, Procedures...)..."
run_command "docker compose exec app php artisan migrate:fresh"

# 10. Importar dados para o Scout (Opcional, mas recomendado)
log_info "Indexando dados para o Scout (Busca)..."
# Usamos \" para garantir que as aspas sejam passadas corretamente para o shell do container
run_command "docker compose exec app php artisan scout:import \"App\Models\Livro\""
run_command "docker compose exec app php artisan scout:import \"App\Models\Autor\""
run_command "docker compose exec app php artisan scout:import \"App\Models\Assunto\""
log_success "Indexação do Scout concluída."

# --- Fim ---
echo ""
log_success "====================================================="
log_success " Ambiente pronto! A aplicação está disponível em:"
log_success " >> http://localhost <<"
log_success "====================================================="
echo ""

exit 0