# Use an official PHP runtime as a parent image
FROM php:8.1-apache
RUN a2enmod rewrite
RUN service apache2 restart

WORKDIR /var/www

# Install Symfony dependencies
RUN apt-get update \
    && apt-get install -y libzip-dev git wget --no-install-recommends \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP extensions
RUN docker-php-ext-install pdo mysqli pdo_mysql zip;

# Install Composer
RUN wget https://getcomposer.org/download/2.7.7/composer.phar \
    && mv composer.phar /usr/bin/composer  \
    && chmod +x /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install composer dependencies
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

# Apache configuration file
COPY /apache.conf /etc/apache2/sites-enabled/000-default.conf

# Entrypoint script
COPY /entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Copy the project contents into the container at /var/www
COPY . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/var/cache/dev

# Expose port 80
EXPOSE 80

# Start Apache web server
CMD ["apache2-foreground"]

ENTRYPOINT ["/entrypoint.sh"]
