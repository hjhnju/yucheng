<?php
//添加应用路径
define('APP_PATH', dirname(dirname(__FILE__)));
//添加配置定义路径
define('CONF_PATH', APP_PATH . '/conf');
//添加环境节点
define('ENVIRON', ini_get('yaf.environ'));

//error_reporting(E_ERROR | E_PARSE);
error_reporting(E_ALL);

ini_set('date.timezone', 'Asia/Shanghai');
ini_set('yaf.library', APP_PATH . "/application/library");

