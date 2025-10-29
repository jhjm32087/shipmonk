FROM php:8.4-alpine

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Update and Install Git
RUN apk add -U --no-cache --update \
    bash \
    git

# Install Default Docker PHP Extentions
RUN install-php-extensions xdebug

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# PHP.ini
ADD docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini