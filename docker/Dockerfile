FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install wait-for-it
RUN curl -o /usr/local/bin/wait-for-it https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh && \
    chmod +x /usr/local/bin/wait-for-it

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd


# Install Composer (latest version)
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Verify Composer installation
RUN composer --version

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set document root to public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy existing application directory contents
COPY . /var/www/html

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Install PHP dependencies (including dev for PHPStan)
RUN composer install --optimize-autoloader

# Verify PHPStan installation
RUN ls -la vendor/bin/phpstan || echo "PHPStan not found"

# Remove storage symlink if it exists (will be recreated in start.sh)
RUN rm -f /var/www/html/public/storage

# Set environment variables for Docker
ENV APP_ENV=local
ENV APP_DEBUG=false
ENV DB_CONNECTION=pgsql
ENV DB_HOST=db
ENV DB_PORT=5432
ENV DB_DATABASE=prosighttask
ENV DB_USERNAME=prosighttask
ENV DB_PASSWORD=password
ENV CACHE_STORE=file
ENV SESSION_DRIVER=file
ENV QUEUE_CONNECTION=sync

# Copy startup script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expose port 80
EXPOSE 80

# Use startup script
CMD ["/usr/local/bin/start.sh"]
