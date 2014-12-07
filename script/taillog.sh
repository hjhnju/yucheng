#!bin/bash

today=`date +'%Y%m%d'`

NGINX_LOG=~/user/hejh/var/access.log
NGINX_ELOG=~/user/hejh/var/error.log

PHP_ELOG=~/var/php_errors.log
BIZ_ELOG=~/var/yucheng/$today/*.log
PHPFPM_ELOG=~/var/php-fpm.log.slow

touch $PHP_ELOG
touch $NGINX_ELOG
touch $BIZ_ELOG

echo "tail $NGINX_LOG $PHP_ELOG $NGINX_ELOG $BIZ_ELOG $PHPFPM_ELOG -f"
tail $BIZ_ELOG $PHP_ELOG $NGINX_ELOG $PHPFPM_ELOG -f
