FROM php:8.4-fpm

ARG user
ARG uid

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git curl libonig-dev libpq-dev libcurl4-openssl-dev unzip zip \
    libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev vim \
    gcc make autoconf libc-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip curl pgsql pdo_pgsql pdo_mysql mbstring exif pcntl bcmath

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# xlswriter
RUN pecl install xlswriter && docker-php-ext-enable xlswriter

# Xdebug
RUN pecl install xdebug-3.4.1 && docker-php-ext-enable xdebug

# Usuário
RUN useradd -G www-data,root -u 1000 -d /home/labs labs \
    && mkdir -p /home/labs/.composer \
    && chown -R labs:labs /home/labs

# Diretório de trabalho
WORKDIR /var/www
COPY . /var/www
RUN chown -R $user:www-data /var/www

# Composer dependencies
RUN composer install --no-dev --optimize-autoloader

USER $user

# Porta que o Render vai detectar
EXPOSE 8080

# PHP Built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
