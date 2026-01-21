# Dockerfile para Lumen + Apache + PHP 8.2

# Imagem base com PHP 8.2 e Apache
FROM php:8.2-apache

LABEL authors="ricardo.cirilo"

# Habilita mod_rewrite para rotas amigáveis do Lumen
RUN a2enmod rewrite

# Instala dependências do PHP necessárias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libonig-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia todo o projeto para dentro do container
COPY . .

# Garante que os diretórios essenciais existem
RUN mkdir -p storage bootstrap/cache

# Permissões corretas para storage e cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Instala Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala dependências do projeto
RUN composer install --no-dev --optimize-autoloader

# Expõe a porta 80
EXPOSE 80

# Comando final para iniciar o Apache
CMD ["apache2-foreground"]
