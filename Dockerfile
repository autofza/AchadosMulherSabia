FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    nodejs \
    npm

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN mkdir -p /var/www/public_uploads_seed \
    && cp -R public/uploads/imgBlogs public/uploads/imgCompanies public/uploads/imgSem.jpg /var/www/public_uploads_seed/

RUN composer install --no-dev --optimize-autoloader

RUN npm install
RUN npm run build

RUN mkdir -p storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

RUN chmod +x docker/nginx/entrypoint.sh

CMD ["bash", "docker/nginx/entrypoint.sh"]
