FROM debian:stable-slim

# Install build tools, C-client libraries, and PHP 8.4
RUN apt-get update && apt-get install -y --no-install-recommends \
    php-fpm \
    php-dev \
    php-pear \
    php-mysql \
    libc-client-dev \
    libkrb5-dev \
    libssl-dev \
    make \
    gcc \
    iproute2 \
    && pecl install imap \
    && echo "extension=imap.so" > /etc/php/8.4/fpm/conf.d/20-imap.ini \
    && echo "extension=imap.so" > /etc/php/8.4/cli/conf.d/20-imap.ini \
    # Clean up build dependencies to keep the image slim
    && apt-get purge -y php-dev php-pear make gcc \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/* /tmp/pipelined-pecl*

COPY config/php.ini /etc/php/8.4/fpm/php.ini
COPY config/php-fpm-www.conf /etc/php/8.4/fpm/pool.d/www.conf
COPY config/php-fpm.conf /etc/php/8.4/fpm/php-fpm.conf

RUN mkdir -p /var/www/html/gyulek && chown -R www-data:www-data /var/www/html

USER www-data

WORKDIR /var/www/html
COPY --chown=www-data app/ /var/www/html/gyulek

EXPOSE 9000

CMD ["php-fpm8.4", "-F"]
