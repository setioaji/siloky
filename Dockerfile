# Gunakan base image PHP resmi dengan Apache
FROM php:8.2-apache

# Install ekstensi yang dibutuhkan (SQLite driver biasanya sudah ada, tapi kita pastikan)
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Aktifkan modul rewrite apache (berguna jika nanti pakai .htaccess)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy semua file source code ke dalam container
COPY . /var/www/html/

# Ubah kepemilikan folder ke user www-data (user default Apache)
# Ini SANGAT PENTING agar SQLite bisa menulis data
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80