FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    libssl-dev \
    pkg-config \
    libcurl4-openssl-dev \
    libicu-dev \
    zlib1g-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-install zip pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install project dependencies
RUN composer update --no-dev --optimize-autoloader

# Expose port for Laravel API
EXPOSE 9000

# Start the Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
