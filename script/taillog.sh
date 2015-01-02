#!bin/bash

today=`date +'%Y%m%d'`

BASEPATH=$HOME'/Dev'
#BASEPATH=$HOME
echo $BASEPATH

NGINX_LOG=$BASEPATH'/var/access.log'
NGINX_ELOG=$BASEPATH'/var/error.log'

PHP_ELOG=$BASEPATH'/var/php_errors.log'
BIZ_ELOG=$BASEPATH"/var/yucheng/$today/*.log"
PHPFPM_ELOG=$BASEPATH'/var/php-fpm.log.slow'

touch $PHP_ELOG
touch $NGINX_ELOG
touch $BIZ_ELOG

echo "tail -f $NGINX_LOG $PHP_ELOG $NGINX_ELOG $BIZ_ELOG $PHPFPM_ELOG"
tail -f $BIZ_ELOG $PHP_ELOG $NGINX_ELOG $PHPFPM_ELOG
