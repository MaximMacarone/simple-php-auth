# Используем базовый образ PHP с поддержкой FPM
FROM php:8.1-fpm

# Устанавливаем необходимые зависимости
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip pdo pdo_mysql

# Устанавливаем Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем содержимое проекта в контейнер
COPY ./web /var/www/html

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Открываем порт
EXPOSE 9000

# Стартуем PHP-FPM
CMD ["php-fpm"]

RUN docker-php-ext-install pdo pdo_mysql