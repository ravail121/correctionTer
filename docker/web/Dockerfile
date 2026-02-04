FROM php:8.2-apache

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update -y \
  && apt-get install -y --no-install-recommends \
    default-mysql-client \
    default-libmysqlclient-dev \
  && docker-php-ext-install mysqli \
  && a2enmod rewrite \
  && a2dismod reqtimeout || true \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*
