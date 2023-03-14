FROM alpine:3.13 as base
RUN apk --no-cache add php81 php81-fpm php81-bcmath php81-ctype php81-json php81-fileinfo \
    php81-mbstring php81-openssl php81-pdo_pgsql php81-curl php81-pdo php81-tokenizer php81-xml \
    php81-opcache nginx curl runit openrc php81-simplexml php81-dom php81-xmlwriter \
    php81-sockets php81-gd php81-iconv php81-xmlreader php81-zip php81-sodium && \
    cp /usr/bin/php81 /usr/bin/php

COPY deployment/docker/fpm-pool.conf /etc/php81/php-fpm.d/www.conf
COPY deployment/docker/php.ini /etc/php81/conf.d/custom.ini

FROM base as build
WORKDIR /build
COPY . /build
RUN apk --no-cache add php81-phar && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && \
    cp .env.example .env && \
    composer install --no-ansi --no-interaction --optimize-autoloader --no-progress --no-dev --profile --prefer-dist

FROM base
COPY deployment/docker/nginx.conf /etc/nginx/nginx.conf
COPY deployment/docker/boot.sh /sbin/boot.sh
COPY deployment/docker/boot-queue.sh /sbin/boot-queue.sh
COPY deployment/docker/nginx.run /etc/service/nginx/run
COPY deployment/docker/php.run /etc/service/php/run
COPY deployment/docker/queue.run /etc/service/queue/run
RUN rm /etc/nginx/conf.d/default.conf
RUN adduser -D -u 1000 -g 1000 -s /bin/sh www && \
    mkdir -p /var/www/html && \
    mkdir -p /var/cache/nginx && \
    chown -R www:www /var/www/html && \
    chown -R www:www /run && \
    chown -R www:www /var/lib/nginx && \
    chown -R www:www /var/log/nginx

COPY --from=build --chown=www /build /var/www/html/

RUN chmod +x /etc/service/nginx/run \
    && chmod +x /etc/service/php/run \
    && chmod +x /etc/service/queue/run

EXPOSE 80

CMD ["sh", "/sbin/boot.sh"]
