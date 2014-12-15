<?php
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Ap调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Base_Bootstrap extends Yaf_Bootstrap_Abstract{

    public function _initNamespace(Yaf_Dispatcher $dispatcher) {
        //初始化命名空间
    }

    public function _initConfig(Yaf_Dispatcher $dispatcher) {
        // 设置配置分节，以适配不同环境
        Base_Config::setOption(array('section' => ini_get('yaf.environ')));
    }

    public function _initView(Yaf_Dispatcher $dispatcher) {
        //在这里注册自己的view控制器，例如smarty,firekylin
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher) {
        //注册一个插件
    }

    public function _initLog(Yaf_Dispatcher $dispatcher) {
        //初始化日志类
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher) {
        //路由协议栈，后入先验证
        //默认已添加Yaf_Route_Static自动划分module/controller/action
    }
}
