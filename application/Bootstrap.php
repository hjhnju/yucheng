<?php
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Ap调用,
 * 这些方法, 都接受一个参数:Ap_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Base_Bootstrap{

    public function _initNamespace(Ap_Dispatcher $dispatcher) {
    }

    public function _initConfig(Ap_Dispatcher $dispatcher) {
        // 设置配置分节，以适配不同环境
        Base_Config::setOption(array('section' => ini_get('ap.environ')));
    }

    public function _initView(Ap_Dispatcher $dispatcher) {
        // 在这里注册自己的view控制器，例如smarty,firekylin
        $smarty = new Smarty_Adapter(null, Base_Config::getConfig("smarty"));
        Ap_Dispatcher::getInstance()->setView($smarty);
    }

    public function _initLog(Ap_Dispatcher $dispatcher) {
        $intUcid = 0;
        Base_Log::setConfigs(array(
                    'level' => Base_Config::getConfig('loglevel'),
                    'logdir'=> Base_Config::getConfig('logpath'),
                    'writehandler' => '_write_with_buf',
                    'ucid' => $intUcid,
                    'ignorepath'=>dirname(dirname(__FILE__)).'/')
                );
        $loglevel = Base_Config::getConfig('loglevel');
    }

    public function _initPlugin(Ap_Dispatcher $dispatcher) {
        //注册一个插件
    }

    public function _initRoute(Ap_Dispatcher $dispatcher) {
        //路由协议栈，后入先验证
        //默认已添加Ap_Route_Static自动划分module/controller/action
        //$router = Ap_Dispatcher::getInstance()->getRouter();
        //$router->addConfig(Base_Config::getConfig('routes', CONF_PATH . '/route.ini'));
    }

    /**
     * 初始化DB配置
     */
    public function _initDb(Ap_Dispatcher $dispatcher) {
        //$conf = Base_Config::getConfig('db', CONF_PATH . '/db.ini');
        //Base_Dao_Factory::getInstance()->setConfig(Base_Config::getConfig('db', CONF_PATH . '/db.ini'));
    }
}
