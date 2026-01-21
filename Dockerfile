# Dockerfile para Lumen + Apache + PHP 8.2
FROM php:8.2-apache

LABEL authors="ricardo.cirilo"

# Habilita mod_rewrite para rotas amigáveis do Lumen
RUN a2enmod rewrite

# Instala extensões PHP necessárias
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

# Copia o projeto para dentro do container
COPY . .

# Cria diretórios essenciais e ajusta permissões
RUN mkdir -p storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Ajusta o DocumentRoot do Apache para o public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|80|8000|g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Instala Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala dependências do projeto
RUN composer install --no-dev --optimize-autoloader

# Expõe a porta 8000
EXPOSE 8000

# Comando para iniciar o Apache
CMD ["apache2-foreground"]
