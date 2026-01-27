FROM php:8.4-fpm

ARG user=labs
ARG uid=1000

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

# Xdebug (opcional em produção)
RUN pecl install xdebug-3.4.1 && docker-php-ext-enable xdebug

# Usuário
RUN useradd -G www-data -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

# Diretório de trabalho
WORKDIR /var/www
COPY . /var/www
RUN chown -R $user:www-data /var/www

# Instala dependências do Composer
USER $user
RUN composer install --no-dev --optimize-autoloader

# Expor porta padrão (Render fornece PORT em runtime)
EXPOSE 8080

# PHP Built-in server com migration
RUN chmod +x /var/www/docker/php/start.sh
CMD ["/var/www/docker/php/start.sh"]
