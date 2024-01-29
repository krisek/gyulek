# based on https://github.com/khromov/alpine-nginx-php8

FROM php:8.2-fpm

# RUN apt install autoconf
RUN apt update && apt install -y php8.2-imap php8.2-mysql php8.2-fpm

COPY config/php.ini /usr/local/etc/php/

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
