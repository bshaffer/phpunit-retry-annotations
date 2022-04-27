FROM php:7.4

WORKDIR /code

COPY . /code/

RUN apt-get update && apt-get install -y \
        git \
        unzip \
   --no-install-recommends && rm -r /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php \
  && mv /code/composer.phar /usr/local/bin/composer \
  && composer install