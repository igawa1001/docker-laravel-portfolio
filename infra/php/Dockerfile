FROM php:8.1-fpm-buster

ENV COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
  apt-get -y install --no-install-recommends git unzip libzip-dev libicu-dev libonig-dev && \
  apt-get clean && \
  rm -rf /var/lib/apt/lists/* && \
  docker-php-ext-install intl pdo_mysql zip bcmath

COPY ./php.ini /usr/local/etc/php/php.ini

# スクリプトファイルをコピー
COPY entrypoint.sh /tmp/
COPY cron.sh /root/script/

# cronをインストール
RUN apt-get update && \
    apt-get install -y cron

# crontabに登録
RUN echo '* * * * * sh /root/script/cron.sh' > /var/spool/cron/crontabs/root
RUN crontab /var/spool/cron/crontabs/root

# エントリーポイントの設定
ENTRYPOINT ["/tmp/entrypoint.sh"]

ENV TZ=Asia/Tokyo

WORKDIR /data