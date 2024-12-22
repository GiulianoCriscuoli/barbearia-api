# Base image com PHP 8.2 (ajuste para 8.3 se preferir)
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libmysqlclient-dev \
    libmbstring-dev \
    libgd-dev

# Instala extensões PHP necessárias para Laravel
RUN docker-php-ext-install pdo_mysql mysqli mbstring gd

# Copia a aplicação para o container
COPY . /var/www/html

# Define o diretório de trabalho
WORKDIR /var/www/html

# Expor a porta 80 do container
EXPOSE 8074
# Configurações do Apache
COPY apache2.conf /etc/apache2/apache2.conf

# Ativa o módulo rewrite para o Laravel
# Configure Apache
COPY apache2.conf /etc/apache2/apache2.conf

# Enable necessary modules (replace with your specific modules)
RUN a2enmod rewrite
RUN a2enmod headers

# Restart Apache
RUN service apache2 restart
