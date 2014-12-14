<?php
define('APP_PATH', dirname(__DIR__) . '/../../');
//添加配置定义路径
define('CONF_PATH', APP_PATH . '/conf');
//添加环境节点
define('ENVIRON', ini_get('yaf.environ'));

$module = 'Loan';
define('MODULE', $module);
define('MODULE_PATH', APP_PATH . '/application/modules/' . $module);
define('MODULE_CONF_PATH', MODULE_PATH . '/conf');
ini_set('date.timezone', 'Asia/Shanghai');
ini_set('yaf.library', APP_PATH . "/application/library");
error_reporting(E_ERROR | E_PARSE);

/**
 * @author jiangsongfang
 */
class ObjectTest extends PHPUnit_Framework_TestCase {
    private $__application = NULL;
    
    // 初始化实例化YAF应用，YAF application只能实例化一次
    public function __construct() {
        if ( ! $this->__application = Yaf_Registry::get('Application') ) {
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
    private function __requestActionAndParseBody($controller, $action, $params=array()) {
        $request = new Yaf_Request_Simple("CLI", "Loan", $controller, $action, $params);
        $response = $this->__application->getDispatcher()
        ->returnResponse(TRUE)
        ->dispatch($request);
        return $response->getBody();
    }

    public function testDefaultFeature() {
        $body = $this->__requestActionAndParseBody('Create', 'index');
        $this->assertEquals($body, 'request');
        
        $_POST = array(
            'user_id' => 1,
            'title' => '借点钱买车',
            'pic' => '',
            'content' => '不说明看能不能借到钱',
            'type_id' => 1,
            'cat_id' => 1,
            'duration' => 12,
            'level' => 1,
            'amount' => 200000,
            'interest' => '12.00',
            'guarantee_type' => 1,
            'audit_info' => '经过审核可以借',
            'deadline' => date("Y-m-d H:i:s", time() + 7 * 24 * 3600),
            'status' => '1',
            'create_uid' => 1,
            'ajax' => 1,
        );
        $_REQUEST = $_POST;
        $body = $this->__requestActionAndParseBody('Create', 'index');
        $res = json_decode($body);
        //添加成功
        $this->assertEquals($res->status, 0);
        var_dump($res);
        $id = $res->data->id;
        
        //failed test
        $_POST['title'] = '';
        $_REQUEST = $_POST;
        $body = $this->__requestActionAndParseBody('Create', 'index');
        $res = json_decode($body);
        var_dump($res);
        $this->assertNotEquals($res->status, 0);
        
        //edit
        var_dump('edit id=', $id);
        $_GET = array(
            'id' => $id,
        );
        unset($_POST);
        $_REQUEST = $_GET;
        $body = $this->__requestActionAndParseBody('Edit', 'index');
        $res = json_decode($body, true);
        var_dump($body, $res);
        $this->assertEquals($id, $res['id']);
        return ;
        
        $res['title'] = $res['title'] . 'edit';
        $title = $res['title'];
        $_POST = $res;
        $_REQUEST = $_POST;
        $body = $this->__requestActionAndParseBody('Edit', 'index');
        $res = json_decode($body, true);
        var_dump($res);
        $this->assertEquals($title, $res['title']);
    }
}
