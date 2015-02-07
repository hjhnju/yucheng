#!bin/bash

function _usage(){
    FILE=`basename $0`
    echo "Modified OK for CentOS release 6.5 (Final)"
    echo "Usage: sh $FILE -t LOG_TYPE -l LOG_LEVEL"
    echo -e "\t-t LOG_TYPE opt, [biz|np]"
    echo -e "\t-l LOG_LEVEL, effective if LOG_TYPE=biz, [all|debug|notice|warn|error]"
    echo -e "\t-h this page"
}

today=`date +'%Y%m%d'`

BASEPATH=$HOME
echo "your base dir:"$BASEPATH

touch $PHP_ELOG
touch $NGINX_ELOG
touch $BIZ_ELOG

NGINX_LOG=$BASEPATH'/var/nginx/access.log'
NGINX_ELOG=$BASEPATH'/var/nginx/error.log'

PHP_ELOG=$BASEPATH'/var/php_errors.log'
PHPFPM_ELOG=$BASEPATH'/var/php-fpm.log.slow'

BIZ_X_LOG=$BASEPATH"/var/yucheng/$today"

function _taillog(){
    local logtype=$1
    local loglevel=$2

    logfiles=""
    if [ $logtype = "np" ];then
        logfiles=$logfiles" "$NGINX_LOG" "$NGINX_ELOG" "$PHP_ELOG" "$PHPFPM_ELOG
    else
        if [ $loglevel = "all" ];then
            logfiles=$logfiles" "$BIZ_X_LOG"/*.log"
        else
            logfiles=$logfiles" "$BIZ_X_LOG"/"$loglevel".log"
        fi
    fi
    echo "tail -f $logfiles"
    tail -f $logfiles
}


if [ $# -eq 0 ]; then
    _usage
    exit 1
fi

LOG_TYPE="all"
LOG_LEVEL="all"
while getopts "t:l:h" opt
do
    case $opt in
        t)
            LOG_TYPE=$OPTARG;;
        l)
            LOG_LEVEL=$OPTARG;;
        h)
            _usage;;
    esac
done

_taillog $LOG_TYPE $LOG_LEVEL
