#!/usr/bin/env sh

readonly HOST=127.0.0.1
readonly PORT=8080

echo 'App listening on http://'$HOST':'$PORT' ...'

php -S $HOST:$PORT -t www/ www/index.php --timeout=0
