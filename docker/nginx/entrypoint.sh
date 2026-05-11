#!/bin/bash
set -e

cd /var/www

# Garante que a pasta de uploads existe no volume
mkdir -p public/uploads/imgProducts public/uploads/imgBlogs public/uploads/imgCompanies

# Popula o volume de uploads com imagens publicas versionadas na imagem Docker
if [ -d /var/www/public_uploads_seed/imgBlogs ]; then
    cp -rn /var/www/public_uploads_seed/imgBlogs/. public/uploads/imgBlogs/
fi

if [ -d /var/www/public_uploads_seed/imgCompanies ]; then
    cp -rn /var/www/public_uploads_seed/imgCompanies/. public/uploads/imgCompanies/
fi

# Garante que a imagem padrao existe no volume caso o volume esteja vazio
if [ ! -f public/uploads/imgSem.jpg ]; then
    if [ -f /var/www/public_uploads_seed/imgSem.jpg ]; then
        cp /var/www/public_uploads_seed/imgSem.jpg public/uploads/imgSem.jpg
    else
        cp public/favicon.png public/uploads/imgSem.jpg
    fi
fi

chmod -R 777 public/uploads

# Limpa e recria caches do Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan storage:link --force

# Sobe o servidor
exec php artisan serve --host=0.0.0.0 --port=80
