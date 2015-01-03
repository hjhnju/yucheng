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

$conf    = new Yaf_Config_INI(CONF_PATH. "/application.ini", ENVIRON);
$modules = explode(',', $conf->get('application.modules'));
$uri     = $_SERVER['REQUEST_URI'];
$intPos  = strpos($uri, '?');
if($intPos !== false){
    $uri = substr($uri, 0, $intPos); 
}
$params  = explode('/', $uri);
$module  = empty($params[1]) ? 'Index' : ucfirst(strtolower($params[1]));
$module  = in_array($module, array('I')) ? 'Awards' : $module;
$module  = in_array($module, $modules) ? $module : 'Index';
define('MODULE', $module);

$conf    = $conf->toArray();
if ($module !== 'Index') {
    //设置module相关常量
    define('MODULE_PATH', APP_PATH . '/application/modules/' . $module);
    define('MODULE_CONF_PATH', MODULE_PATH . '/conf');
    //改变应用所在路径
    //$conf['application']['baseUri']   = '/' . $module;
    //$conf['application']['directory'] = MODULE_PATH;
    //增加模块本地库路径
    $conf['application']['library']   = MODULE_PATH . '/library';
    //增加模块Bootstrap
    $conf['application']['bootstrap'] = MODULE_PATH . '/Bootstrap.php';
    $conf['smarty']['template_dir']   = MODULE_PATH . '/views';
}
try {
    $application = new Yaf_Application($conf);
    $application->bootstrap()->run();
} catch (Exception $e) {
    var_dump($e->getMessage());die;
    Base_Log::error($e->getMessage());
}

