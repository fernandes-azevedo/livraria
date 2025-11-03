<?php

// Função para executar um comando e imprimir a saída
function runCommand($command) {
    echo "Executando comando: $command\n";
    $process = proc_open($command, [
        0 => STDIN,
        1 => STDOUT,
        2 => STDERR,
    ], $pipes);

    if (is_resource($process)) {
        $return_code = proc_close($process);
        if ($return_code !== 0) {
            echo "Erro ao executar o comando: $command\n";
            exit($return_code);
        }
    }
}

// 1. Instalar dependências do Composer
if (!file_exists('vendor/autoload.php')) {
    runCommand('composer install');
}

// 2. Criar arquivo .env
if (!file_exists('.env')) {
    copy('.env.example', '.env');
    echo "Arquivo .env criado.\n";
}

// 3. Gerar chave da aplicação
// Precisamos verificar se a chave já está definida
$envContent = file_get_contents('.env');
if (strpos($envContent, 'APP_KEY=') === false || strpos($envContent, 'APP_KEY=') > strpos($envContent, 'APP_KEY=base64:')) {
    runCommand('php artisan key:generate');
}

// 4. Criar arquivo de banco de dados SQLite
if (!file_exists('database/database.sqlite')) {
    touch('database/database.sqlite');
    echo "Arquivo database/database.sqlite criado.\n";
}

// 5. Executar migrations e seeding do banco de dados
runCommand('php artisan migrate:fresh --seed');

// 6. Instalar dependências do NPM
if (!file_exists('node_modules')) {
    runCommand('npm install');
}

// 7. Iniciar servidores de desenvolvimento
echo "Iniciando servidores de desenvolvimento...\n";
echo "Você já pode acessar a aplicação em http://127.0.0.1:8000\n";
runCommand('npm start');

