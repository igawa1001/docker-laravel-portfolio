#!/bin/sh

# 環境変数設定スクリプト作成
printenv | awk '{print "export " $1}' > /root/env.sh

# cron 起動
service cron start

# php-fpm 起動
docker-php-entrypoint php-fpm