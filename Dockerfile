FROM composer:1.0

RUN mkdir -p /usr/src/RestaurangOnlineDevelopmentConsole
WORKDIR /usr/src/RestaurangOnlineDevelopmentConsole

COPY . /usr/src/RestaurangOnlineDevelopmentConsole

RUN composer install

ENTRYPOINT ["php", "workspace"]