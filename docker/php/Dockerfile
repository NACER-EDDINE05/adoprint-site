FROM php:8.3-fpm

# System deps
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mbstring exif intl xml zip bcmath

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create system user
RUN useradd -G www-data,root -u 1000 -d /home/developer developer || true

WORKDIR /var/www/html

# Permissions
RUN chown -R developer:developer /var/www/html

# Expose
EXPOSE 9000

CMD ["php-fpm"]
