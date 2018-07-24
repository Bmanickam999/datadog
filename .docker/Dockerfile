ARG PHP_VERSION=7.2
FROM php:${PHP_VERSION}-alpine

ARG XDEBUG=0

COPY .docker/entrypoint.sh /usr/local/bin/

RUN apk add --no-cache \
	autoconf \
	make \
	g++ \
	bash \
	git \
	openssl-dev

RUN echo -e 'memory_limit=2G' > /usr/local/etc/php/conf.d/memory.ini
RUN set -o pipefail && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /docker
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
