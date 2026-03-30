FROM php:8.2-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libonig-dev libxml2-dev \
    && docker-php-ext-install bcmath mbstring pdo_mysql xml \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .

RUN php artisan package:discover --ansi \
    && chmod +x render/predeploy.sh render/start.sh

EXPOSE 10000

CMD ["./render/start.sh"]
