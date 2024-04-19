#!/bin/sh

# 環境変数を設定
. /root/env.sh

# 定期実行したい処理
date 1>/proc/1/fd/1
/usr/local/bin/php /data/artisan schedule:run >> /var/log/cron.log 2>&1