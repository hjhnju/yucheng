<?php
/**
 *用户财务类相关操作
 */
class PayController extends Base_Controller_Api{

    
    public function init(){
    	$this->setNeedLogin(false);
        parent::init();
    }

    public function testAction(){
        $ret = Finance_Api::queryBalanceBg("6000060000696947");
        var_dump($ret);
    }


}

