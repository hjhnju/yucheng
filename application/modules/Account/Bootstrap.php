<?php
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Ap调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Base_Bootstrap{

    public function _initNamespace(Yaf_Dispatcher $dispatcher) {
        parent::_initNamespace($dispatcher);
        Yaf_Loader::getInstance()->registerLocalNamespace(array('Api'));
    }
 
    public function _initConfig(Yaf_Dispatcher $dispatcher) {
        parent::_initConfig($dispatcher);
    }

    public function _initView(Yaf_Dispatcher $dispatcher) {
        parent::_initView($dispatcher);
        $smarty = new Smarty_Adapter(null, Base_Config::getConfig("smarty"));
        Yaf_Dispatcher::getInstance()->setView($smarty);
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher) {
        //注册一个插件
        parent::_initPlugin($dispatcher); 
    }

    public function _initLog(Yaf_Dispatcher $dispatcher) {
        //无法做checklogin操作。因为依赖于本地的db配置还没加载
        Base_Log::setConfigs(array(
            'level'        => Base_Config::getConfig('loglevel'),
            'logdir'       => Base_Config::getConfig('logpath'),
            'writehandler' => '_write_with_buf',
            'ucid'         => 0,
            'ignorepath'   =>dirname(dirname(__FILE__)).'/')
        );
        $loglevel = Base_Config::getConfig('loglevel');
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher) {
       // $router = Yaf_Dispatcher::getInstance()->getRouter();
      //  $router->addConfig(Base_Config::getConfig('routes', CONF_PATH . '/route.ini'));
       // $routes = $router->getRoutes();
    }
}
