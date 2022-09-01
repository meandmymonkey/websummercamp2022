FROM composer:2.4.1 AS composer

FROM php:8.1-cli

RUN docker-php-source extract \
    && apt update \
    && apt install -y git librabbitmq-dev \
    && git clone --branch master --depth 1 https://github.com/php-amqp/php-amqp.git /usr/src/php/ext/amqp \
    && cd /usr/src/php/ext/amqp && git submodule update --init \
    && docker-php-ext-install amqp

RUN docker-php-ext-install pcntl

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt install -y gnupg \
    && curl -SsL https://packages.httpie.io/deb/KEY.gpg | apt-key add - \
	&& curl -SsL -o /etc/apt/sources.list.d/httpie.list https://packages.httpie.io/deb/httpie.list \
	&& apt update \
	&& apt install -y httpie

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash \
    && apt install symfony-cli

WORKDIR /srv/app

RUN apt install -y unzip

CMD ["/bin/bash", "-c", "symfony serve"]