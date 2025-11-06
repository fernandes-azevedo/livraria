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
    if ! $1; then
        log_error "O comando falhou. Abortando o script."
        # Tenta parar os containers em caso de falha
        docker compose down
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

# 2. Subir os serviços de backend (MySQL, Redis) e o Node.
#    Note que 'app' e 'nginx' NÃO são iniciados aqui.
log_info "Iniciando serviços de base (mysql, redis, node)..."
run_command "docker compose up -d --build mysql redis node"
log_success "Serviços de base estão no ar!"

# 3. Construir a imagem do 'app' (PHP-FPM), mas não iniciar o serviço
log_info "Construindo a imagem do container 'app' (PHP-FPM)..."
run_command "docker compose build app"

# 4. Instalar dependências do Composer (O Pulo do Gato)
#    Usamos 'run --rm' para criar um container 'app' temporário
#    que executa 'composer install' e depois é destruído.
#    Isso cria a pasta 'vendor/' no volume compartilhado.
log_info "Instalando dependências do Composer..."
run_command "docker compose run --rm app composer install --no-interaction --prefer-dist --optimize-autoloader"
log_success "Pasta 'vendor/' criada."

# 5. Agora, iniciar 'app' e 'nginx'
#    O 'app' (PHP-FPM) agora vai iniciar com sucesso, pois 'vendor/' existe.
#    O 'nginx' agora poderá se conectar ao 'app'.
log_info "Iniciando os serviços 'app' (PHP-FPM) e 'nginx' (Webserver)..."
run_command "docker compose up -d app nginx"
log_success "Aplicação está no ar!"

# 6. Aguardar o MySQL (que já estava subindo)
log_info "Aguardando o contêiner do MySQL (15 segundos)..."
sleep 15

# 7. Gerar a Chave da Aplicação
log_info "Gerando a chave da aplicação (APP_KEY)..."
run_command "docker compose exec app php artisan key:generate"

# 8. Limpar caches (para garantir que o .env seja lido)
log_info "Limpando caches de configuração..."
run_command "docker compose exec app php artisan optimize:clear"

# 9. Rodar as Migrações
log_info "Executando as migrações do banco de dados (Triggers, Procedures...)..."
run_command "docker compose exec app php artisan migrate:fresh"

# --- Fim ---
echo ""
log_success "====================================================="
log_success " Ambiente pronto! A aplicação está disponível em:"
log_success " >> http://localhost <<"
log_success "====================================================="
echo ""

exit 0