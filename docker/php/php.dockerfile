FROM php:8.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libonig-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    vim \
    gcc \
    make \
    autoconf \
    libc-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure GD & ZIP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip curl pgsql pdo_pgsql pdo_mysql mbstring exif pcntl bcmath

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xlswriter via PECL
RUN pecl install xlswriter \
    && docker-php-ext-enable xlswriter

# Install Xdebug
RUN pecl install xdebug-3.4.1 \
    && docker-php-ext-enable xdebug

# Create system user
RUN useradd -G www-data,root -u 1000 -d /home/labs labs && \
    mkdir -p /home/labs/.composer && \
    chown -R labs:labs /home/labs

# Set working directory
RUN chown -R $user:www-data /var/www
WORKDIR /var/www
USER $user
