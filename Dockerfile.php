FROM php:8.3-fpm

# Installe les extensions PHP nécessaires à WordPress
RUN apt-get update \
    && apt-get install -y \
        libjpeg-dev \
        libpng-dev \
        libwebp-dev \
        libfreetype6-dev \
        libzip-dev \
        libicu-dev \
        libmagickwand-dev \
        procps \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        mysqli \
        pdo \
        pdo_mysql \
        gd \
        exif \
        zip \
        intl \
    && pecl install imagick \
    && docker-php-ext-enable \
        mysqli \
        pdo_mysql \
        imagick \
    && rm -rf /var/lib/apt/lists/*

# Copie le script d'entrypoint custom
COPY setup-wordpress.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/setup-wordpress.sh

# Utilise le script comme entrypoint
ENTRYPOINT ["bash", "/usr/local/bin/setup-wordpress.sh"]
