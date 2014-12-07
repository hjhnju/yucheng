<?php
/**
 * 
 */
class IndexController extends Base_Controller_Page {
	
	public function indexAction() {
        $redis = Base_Redis::getInstance();
        $ret   = $redis->set('xjd_version', '1.0');
        print_r($ret);
        $ret   = $redis->del('xjd_version');
        print_r($ret);

        $model = new TestModel();
        $res = $model->query();
        print_r($res);
		exit();
	}
}
