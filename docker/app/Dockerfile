# Usar a imagem oficial do PHP 8.3 com FPM [fonte 1]
FROM php:8.3-fpm-alpine

# Argumentos para usuário e grupo
ARG UID
ARG GID

# Instalar dependências do sistema e do PHP
RUN apk add --no-cache \
    curl git openssl zip libzip libxml2 libpng libjpeg-turbo freetype icu-libs \
    mysql-client \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    build-base \
    linux-headers \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev

# Instalar extensões PHP necessárias para o Laravel
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    intl \
    bcmath \
    mbstring \
    exif \
    pcntl \
    zip \
    opcache \
    sockets

# Instalar GD [fonte 4]
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Instalar Redis via PECL [fonte 5]
RUN pecl install redis \
    && docker-php-ext-enable redis

# Limpar dependências de build [fonte 5]
RUN apk del .build-deps

# Instalar Composer globalmente [fonte 5]
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário e grupo para a aplicação [fonte 5]
RUN addgroup -g ${GID:-1000} laravel && \
    adduser -u ${UID:-1000} -G laravel -s /bin/sh -D laravel

# Mudar para o usuário da aplicação [fonte 5]
USER laravel

# Definir o diretório de trabalho [fonte 5]
WORKDIR /var/www/html

# Expor a porta do PHP-FPM [fonte 5]
EXPOSE 9000

# Comando padrão para iniciar o PHP-FPM [fonte 5]
CMD ["php-fpm"]