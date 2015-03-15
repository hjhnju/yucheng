<?php
//xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

//添加应用路径
define('APP_PATH',dirname(dirname(__FILE__)));
//添加配置定义路径
define('CONF_PATH', APP_PATH . '/conf');
//添加环境节点
define('ENVIRON', ini_get('yaf.environ'));

error_reporting(E_ALL);
ini_set('date.timezone', 'Asia/Shanghai');
ini_set('yaf.library', APP_PATH . "/application/library");

try {
    $application = new Yaf_Application(CONF_PATH . "/application.ini");
    $application->bootstrap();
} catch (Exception $e) {
    var_dump($e->getMessage());die;
}

