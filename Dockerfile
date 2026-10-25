FROM debian:stable-slim

RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates \
    curl \
    lsb-release \
    gnupg2 \
    iproute2 \
    && curl -sSLo /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
    && echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list \
    && apt-get update && apt-get install -y --no-install-recommends \
    php8.4-fpm \
    php8.4-mysql \
    php8.4-imap \
    && rm -rf /var/lib/apt/lists/*

COPY config/php.ini /etc/php/8.4/fpm/php.ini
COPY config/php-fpm-www.conf /etc/php/8.4/fpm/pool.d/www.conf
COPY config/php-fpm.conf /etc/php/8.4/fpm/php-fpm.conf

RUN mkdir -p /var/www/html/gyulek && chown -R www-data:www-data /var/www/html

USER www-data

WORKDIR /var/www/html
COPY --chown=www-data app/ /var/www/html/gyulek

EXPOSE 9000

CMD ["php-fpm8.4", "-F"]
