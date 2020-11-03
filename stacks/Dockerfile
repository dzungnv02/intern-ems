FROM php:7.4.3-fpm-alpine3.10

RUN apk add --no-cache \
		ca-certificates \
		curl \
		tar \
		xz \
		openssl \
		autoconf
		
        
RUN apk add --no-cache --virtual .mongodb-ext-build-deps git gcc make g++ zlib-dev nodejs npm

COPY docker-php-ext-* docker-php-entrypoint docker-php-composer-install /usr/local/bin/
COPY docker-php-composer-install docker-php-lumen-install /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-php-composer-install /usr/local/bin/docker-php-lumen-install
# # sodium was built as a shared module (so that it can be replaced later if so desired), so let's enable it too (https://github.com/docker-library/php/issues/598)


RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
  docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg && \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j${NPROC} gd && \
  apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev


RUN apk add --no-cache libmcrypt-dev rabbitmq-c-dev \
	&& pecl install mcrypt \
	&& docker-php-ext-enable mcrypt \
	&& pecl install amqp \
	&& docker-php-ext-enable amqp

RUN pear install PHP_Archive-0.13.0
RUN pecl install mongodb

RUN apk add --no-cache libzip-dev postgresql-libs postgresql-dev\ 
	&& docker-php-ext-install zip pdo_pgsql sockets \
	&& docker-php-ext-enable sodium mongodb \
	&& docker-php-ext-configure zip \
	&& docker-php-composer-install \
	&& docker-php-lumen-install \
	&& apk del postgresql-dev \
	&& docker-php-ext-install mysqli pdo_mysql

RUN docker-php-ext-install pcntl

RUN apk add --update supervisor && rm  -rf /tmp/* /var/cache/apk/*
ADD supervisord.conf /etc/

ADD https://github.com/ufoscout/docker-compose-wait/releases/download/2.2.1/wait /wait
RUN chmod +x /wait

WORKDIR /var/www/html

EXPOSE 9000
CMD /wait && supervisord --nodaemon --configuration /etc/supervisord.conf