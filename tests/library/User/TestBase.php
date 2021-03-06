<?php
$module = 'User';
define('MODULE', $module);
define('MODULE_PATH', APP_PATH . '/application/modules/' . $module);

/**
 * @author jiangsongfang
 */
class TestBase extends PHPUnit_Framework_TestCase {
    private $__application = NULL;
    
    // 初始化实例化YAF应用，YAF application只能实例化一次
    public function __construct() {
        if (!$this->__application = Yaf_Registry::get('Application')) {
            $conf    = new Yaf_Config_INI(CONF_PATH. "/application.ini", ENVIRON);
            $conf    = $conf->toArray();
            $conf['application']['library']   = MODULE_PATH . '/library';
            $conf['application']['bootstrap'] = MODULE_PATH . '/Bootstrap.php';
            $conf['smarty']['template_dir']   = MODULE_PATH . '/views';
            $this->__application = new Yaf_Application($conf);
            $this->__application->bootstrap();
            Yaf_Registry::set('Application', $this->__application);
        }
    }
    
    // 创建一个简单请求，并利用调度器接受Repsonse信息，指定分发请求。
    protected function __requestActionAndParseBody($controller, $action, $params=array()) {
        $request  = new Yaf_Request_Simple("CLI", MODULE, $controller, $action, $params);
        $response = $this->__application->getDispatcher()
        ->returnResponse(TRUE)
        ->dispatch($request);
        return $response->getBody();
    }
}
