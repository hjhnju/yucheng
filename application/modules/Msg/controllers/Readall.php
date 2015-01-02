<?php
/**
 * 标记所有消息已读
 */
class ReadallController extends Base_Controller_Api {
    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
	
	public function indexAction() {
        $uid = trim($_REQUEST['uid']);
        $this->msgLogic = new Msg_Logic_Msg();
        $ret = $this->msgLogic->setReadAll($uid);
        if($ret){
            return $this->ajax();
        }
        return $this->ajaxError();
	}
}
