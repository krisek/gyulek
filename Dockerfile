# based on https://github.com/khromov/alpine-nginx-php8

FROM debian:stable-slim

# RUN apt install autoconf
RUN apt update && apt install -y php-imap php-mysql php-fpm iproute2

COPY config/php.ini /etc/php/8.2/fpm/php.ini

COPY config/php-fpm-www.conf /etc/php/8.2/fpm/pool.d/www.conf
COPY config/php-fpm.conf /etc/php/8.2/fpm/php-fpm.conf


# Setup document root
RUN mkdir -p /var/www/html/gyulek

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R www-data:www-data /var/www/html


# Switch to use a non-root user from here on
USER www-data

# Add application
WORKDIR /var/www/html
COPY --chown=www-data app/ /var/www/html/gyulek

# Expose the port nginx is reachable on
EXPOSE 9000

CMD php-fpm8.2 