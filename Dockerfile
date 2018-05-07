FROM composer/composer:1.1

RUN mkdir -p /usr/src/RestaurangOnlineDevelopmentConsole
WORKDIR /usr/src/RestaurangOnlineDevelopmentConsole

COPY . /usr/src/RestaurangOnlineDevelopmentConsole

RUN sed -i -e"s/^read_only/#read_only/" /etc/php/

RUN composer install

ENTRYPOINT ["php", "workspace"]