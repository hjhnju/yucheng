<?php
define('APP_PATH', dirname(__DIR__) . '/../../');
//添加配置定义路径
define('CONF_PATH', APP_PATH . '/conf');
//添加环境节点
define('ENVIRON', ini_get('yaf.environ'));
//error_reporting(E_ERROR | E_PARSE);

/**
 * @author hejunhua <hejunhua@baidu.com>
 * @since 2013-12-05
 */
class ObjectTest extends PHPUnit_Framework_TestCase {
    private $__application = NULL;
    
    // 初始化实例化YAF应用，YAF application只能实例化一次
    public function __construct() {
        if ( ! $this->__application = Yaf_Registry::get('Application') ) {
            $this->__application = new Yaf_Application(APP_PATH."/conf/application.ini");
            Yaf_Registry::set('Application', $this->__application);
        }
    }
    
    // 创建一个简单请求，并利用调度器接受Repsonse信息，指定分发请求。
    private function __requestActionAndParseBody($controller, $action, $params=array()) {
        $request = new Yaf_Request_Simple("CLI", "Loan", $controller, $action, $params);
        $response = $this->__application->getDispatcher()
        ->returnResponse(TRUE)
        ->dispatch($request);
        return $response->getBody();
    }

    public function testDefaultFeature() {
        $log = new Loan_Object_Log();
        $log->loanId = 1;
        $log->userId = 2;
        $log->ip = '127.0.0.1';
        $log->content = 'hello kitty';
        $log->save();
        $data = $log->toArray();
        var_dump($data);
        
        $id = $log->id;
        var_dump('update', $id);
        $log->content = 'update hello';
        $log->save();
        $data2 = $log->toArray();
        $this->assertNotEquals($data, $data2);
        
        var_dump('fetch', $id);
        
        $log = new Loan_Object_Log($id);
        $data3 = $log->toArray();
        var_dump($data3);

        $this->assertEquals($data2, $data3);
        
        $log->remove();
        $log = new Loan_Object_Log($id);
        $this->assertEquals($log->content, null);
    }
}
