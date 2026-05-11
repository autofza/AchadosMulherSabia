#!/bin/bash
set -e

cd /var/www

# Garante que a pasta de uploads existe no volume
mkdir -p public/uploads/imgProducts

# Garante que a imagem padrao existe no volume caso o volume esteja vazio
if [ ! -f public/uploads/imgSem.jpg ]; then
    cp public/favicon.png public/uploads/imgSem.jpg
fi

chmod -R 777 public/uploads

# Limpa e recria caches do Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan storage:link --force

# Sobe o servidor
exec php artisan serve --host=0.0.0.0 --port=80
