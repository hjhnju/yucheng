<?php
/**
 * 
 */
class IndexController extends Base_Controller_Abstract {
	
	public function indexAction() 
	{
        $model = new TestModel();
        $res = $model->query();
        print_r($res);
		exit();
	}
}
