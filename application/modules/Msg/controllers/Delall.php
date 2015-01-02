<?php
/**
 * 删除所有消息
 */
class DelallController extends Base_Controller_Api {
	
    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }

	public function indexAction() {
        $uid = trim($_REQUEST['uid']);
        $this->msgLogic = new Msg_Logic_Msg();
        $ret = $this->msgLogic->delAll($uid);
        if($ret){
            return $this->ajax();
        }
        return $this->ajaxError();
	}
}
