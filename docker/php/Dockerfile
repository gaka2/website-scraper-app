FROM php:8.1-cli

ARG USER
ARG USER_ID
ARG PWD=nIaLNOw
RUN useradd -m ${USER} --uid=${USER_ID} && echo "${USER}:${PWD}" | \
      chpasswd

WORKDIR "/var/www/html/app"

RUN apt-get update \
    && apt-get install -y git zip

#needed for code coverage analysis
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

#PHP intl extension
RUN apt-get -y update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

#composer version 2
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --2

#keep container running
CMD tail -f /dev/null
