FROM richarvey/nginx-php-fpm:latest

# Install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Copy application files
COPY . .

# Generate optimized autoloader and run scripts
RUN composer dump-autoload --optimize

# Image config
ENV SKIP_COMPOSER 0
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Run the build script
RUN chmod +x build.sh && ./build.sh

CMD ["/start.sh"] 