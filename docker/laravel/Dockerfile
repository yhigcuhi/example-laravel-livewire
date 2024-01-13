# php
FROM php:8.2-fpm

# 時間 東京
ENV TZ="Asia/Tokyo"
# 独自の php.ini
COPY ./php.ini /usr/local/etc/php/php.ini
# Composer install:PHP ライブラリ管理とかのやつ
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# install Node.js: Vue開発用
COPY --from=node:20 /usr/local/bin /usr/local/bin
COPY --from=node:20 /usr/local/lib /usr/local/lib

# コンテナ内(Ubuntu)のOSにインストールしておくやつ
# ミドルウェア インストール
RUN apt-get update && \
    apt-get -y install \
    git \
    zip \
    unzip \
    vim \
    zlib1g-dev \
    # mysql用のインストール
    && apt-get -y install mariadb-client \
    # PDO_MYSQL で php用のドライバ
    && docker-php-ext-install pdo_mysql

# RUN , CMD , ENTRYPOINT , COPY , ADD を実行する時のワーキングディレクトリを指定
WORKDIR /var/www/html