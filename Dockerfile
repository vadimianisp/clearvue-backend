FROM php:8.2-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Allow .htaccess overrides by setting AllowOverride All for all directories
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Change DocumentRoot to point to the public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|g' /etc/apache2/sites-available/000-default.conf

# Install PDO and MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Redis extension via PECL and enable it
RUN pecl install redis && docker-php-ext-enable redis

# Set the working directory
WORKDIR /var/www
