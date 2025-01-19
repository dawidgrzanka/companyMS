FROM richarvey/nginx-php-fpm:latest

# Install Node.js and npm
RUN apk add --update nodejs npm

# Install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Copy package files and install npm dependencies
COPY package.json package-lock.json ./
RUN npm install

# Copy application files
COPY . .

# Build assets and optimize
RUN npm run build
RUN composer dump-autoload --optimize

# Laravel configuration
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# Nginx and PHP configuration
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV SKIP_COMPOSER 1

# Set up Laravel application
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan storage:link

# Set correct permissions
RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["/start.sh"] 