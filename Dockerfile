# Use PHP 8.2 instead of 8.1
FROM php:8.2-fpm

# There was an error installing this container so I had to run docker builder prune and then rebuild the container
# Clear the package cache and update the GPG keys
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nginx \
    supervisor \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install NVM
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.38.0/install.sh | bash

# Activate NVM and install Node 14
RUN /bin/bash -c "source ~/.nvm/nvm.sh && nvm install 14 && nvm alias default 14 && nvm use default"

# Confirm Node version
RUN /bin/bash -c "source ~/.nvm/nvm.sh && node -v && npm -v"

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Remove default Nginx configuration
RUN rm /etc/nginx/sites-enabled/default

# Copy Nginx configuration
COPY nginx.conf /etc/nginx/sites-enabled/

# Copy Supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/

# Remove the default public folder
RUN rm -rf /var/www/html

# Copy existing application directory contents
COPY . /var/www

# Install NPM packages
RUN /bin/bash -c "source ~/.nvm/nvm.sh && npm install"

# Install Composer requirements
RUN composer install --no-scripts

# Change ownership for the storage and bootstrap/cache directories
RUN chown -R www-data:www-data /var/www
RUN chmod -R 777 ./
RUN /bin/bash -c "source ~/.nvm/nvm.sh && npm run prod"

# Expose ports
EXPOSE 80
EXPOSE 3000
EXPOSE 8080
EXPOSE 8000

# Start Nginx and PHP-FPM using Supervisor
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
