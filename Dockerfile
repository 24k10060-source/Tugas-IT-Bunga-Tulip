# Dockerfile untuk Deployment di Render / Railway
FROM php:8.2-apache

# Install ekstensi PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Copy seluruh file aplikasi ke document root Apache
COPY . /var/www/html/

# Set permission direktori
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

CMD ["apache2-foreground"]
