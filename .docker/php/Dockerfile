FROM php:8.1-fpm-alpine

# ARG SHOPIFY_API_KEY
# ENV SHOPIFY_API_KEY=$SHOPIFY_API_KEY

RUN apk add --no-cache openssl bash mysql-client
RUN docker-php-ext-install pdo pdo_mysql

RUN apk update && apk add --update nodejs npm \
    composer php-pdo_sqlite php-pdo_mysql mysql-client  php-pdo_pgsql php-simplexml php-fileinfo php-dom php-tokenizer php-xml php-xmlwriter php-session \
    openrc bash nginx

# COPY --chown=www-data:www-data . /var/www
WORKDIR /var/www
COPY ./php.ini.development "$PHP_INI_DIR/php.ini"

# Use the default production configuration
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
