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
    libcurl3-dev \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    vim \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ZIP & GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


#=====
# Install PHP extensions
#=====
## Laravel's dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install curl pgsql pdo_pgsql pdo_mysql mbstring exif pcntl bcmath

#=====xdebug
RUN pecl install xdebug-3.4.1 \
    && docker-php-ext-enable xdebug
#=====xdebug

#=====PHPGD
#RUN apt install -y \
#    libpng-dev \
#    libfreetype6-dev \
#    libjpeg62-turbo-dev \
#  && docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
#  && docker-php-ext-install gd
#  && apt cache clear
#=====PHPGD

#=====Imagic
#RUN apt install -y libmagickwand-dev \
#    && pecl install imagick \
#    && docker-php-ext-enable imagick \
#    && apt clean
#=====Imagic

# Create system user to run Composer and Artisan Commands
# Create system user
RUN useradd -G www-data,root -u 1000 -d /home/labs labs && \
    mkdir -p /home/labs/.composer && \
    chown -R labs:labs /home/labs


# Set working directory
RUN chown -R $user:www-data /var/www

WORKDIR /var/www

USER $user
