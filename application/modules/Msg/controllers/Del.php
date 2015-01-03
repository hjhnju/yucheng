<?php
/**
 * 删除消息
 */
class DelController extends Base_Controller_Api {
    public function init(){
        $this->setNeedLogin(true);
        parent::init();
    }
	
	public function indexAction() {
        $this->msgLogic = new Msg_Logic_Msg();
        $mid = trim($_REQUEST['mid']);
        $ret = $this->msgLogic->del($mid);
        if($ret){
            return $this->ajax();
        }
        return $this->ajaxError();
	}
}
