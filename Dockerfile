# Gunakan image PHP resmi dengan ekstensi yang dibutuhkan Laravel/Filament
FROM php:8.3-fpm

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    libpq-dev libzip-dev libicu-dev

# Bersihkan cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP (Wajib Filament: gd, intl, zip, pdo_mysql)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl


# Ambil Composer terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project ke container
COPY . .

# Buat file .env sementara dari example agar composer tidak error
RUN cp .env.example .env

# Install dependencies Laravel (tanpa dev untuk produksi)
# RUN composer install --no-dev --optimize-autoloader
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Atur permission untuk storage dan cache (PENTING)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Jalankan PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]